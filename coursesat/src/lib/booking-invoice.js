"use client";

import { formatCurrency, formatDate } from "@/lib/format";
import { getBackendOrigin, getImageUrl } from "@/lib/api";

const INVOICE_CONTACT_EMAIL = "booking@pioneersedu.com";
const INVOICE_CONTACT_WHATSAPP = "+966 55 002 7268";

function esc(value) {
  return String(value ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

function absAsset(path) {
  return resolveInvoiceAssetUrl(path);
}

function resolveInvoiceAssetUrl(path) {
  if (!path) return "";
  const trimmed = String(path).trim();
  if (!trimmed) return "";
  if (trimmed.startsWith("data:")) return trimmed;

  const resolved = getImageUrl(trimmed) || trimmed;

  if (resolved.startsWith("http://") || resolved.startsWith("https://")) {
    const storageIndex = resolved.indexOf("/storage/");
    if (storageIndex !== -1 && typeof window !== "undefined") {
      return `${window.location.origin}${resolved.slice(storageIndex)}`;
    }
    return resolved;
  }

  if (resolved.startsWith("/storage/")) {
    if (typeof window !== "undefined") {
      return `${window.location.origin}${resolved}`;
    }
    return `${getBackendOrigin()}${resolved}`;
  }

  if (typeof window !== "undefined") {
    return `${window.location.origin}${resolved.startsWith("/") ? resolved : `/${resolved}`}`;
  }

  return resolved;
}

function blobToDataUrl(blob) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(String(reader.result || ""));
    reader.onerror = () => reject(reader.error);
    reader.readAsDataURL(blob);
  });
}

async function fetchAsDataUrl(url) {
  if (!url) return "";
  if (url.startsWith("data:")) return url;
  try {
    const response = await fetch(url, { credentials: "same-origin" });
    if (!response.ok) return "";
    const blob = await response.blob();
    if (!blob.size) return "";
    return blobToDataUrl(blob);
  } catch {
    return "";
  }
}

async function prepareInlineInvoiceImages(booking) {
  const courseSatLogo = await fetchAsDataUrl(resolveInvoiceAssetUrl("/assets/logo/logo.png"));
  const schoolPath = booking.school_logo || booking.course_image;
  const schoolLogo = schoolPath ? await fetchAsDataUrl(resolveInvoiceAssetUrl(schoolPath)) : "";
  const banks = Array.isArray(booking.payment_banks) ? booking.payment_banks : [];
  const bankLogos = await Promise.all(
    banks.map((bank) =>
      bank.logo_url ? fetchAsDataUrl(resolveInvoiceAssetUrl(bank.logo_url)) : Promise.resolve(""),
    ),
  );

  return { courseSatLogo, schoolLogo, bankLogos };
}

function imgTag(src, alt, style) {
  if (!src) return "";
  const safeAlt = esc(alt);
  const safeSrc = esc(src);
  const safeStyle = style || "max-height:48px;max-width:140px;object-fit:contain";
  return `<img src="${safeSrc}" alt="${safeAlt}" style="${safeStyle}"/>`;
}

function lineLabel(line, isArabic) {
  if (!line) return "";
  if (isArabic) {
    return esc(line.ar_label || line.label || "");
  }
  return esc(line.label || line.ar_label || "");
}

function lineDetail(line, isArabic) {
  if (!line) return "";
  const detail = isArabic ? line.ar_detail || line.detail : line.detail || line.ar_detail;
  return detail ? esc(detail) : "";
}

export function buildBookingInvoiceHtml(booking, { isArabic = false, labels = {}, forPdf = false, inlineImages = null } = {}) {
  if (!booking) return "";

  const L = (key, fallback) => labels[key] || fallback;
  const dir = isArabic ? "rtl" : "ltr";
  const lang = isArabic ? "ar" : "en";
  const currency = booking.currency || "SAR";
  const bookingId = booking.reference_no || booking.booking_id || booking.id || "-";
  const schoolName = isArabic
    ? booking.school_ar_name || booking.school_name
    : booking.school_name || booking.school_ar_name;
  const courseName = isArabic
    ? booking.course_ar_name || booking.course_name
    : booking.course_name || booking.course_ar_name;
  const total = booking.final_price ?? booking.total ?? 0;
  const courseSatLogo = inlineImages?.courseSatLogo || absAsset("/assets/logo/logo.png");
  const schoolLogoSrc = inlineImages?.schoolLogo || absAsset(booking.school_logo || booking.course_image);

  const feeLines = Array.isArray(booking.fee_lines) ? booking.fee_lines : [];
  const discountLines = Array.isArray(booking.discount_lines) ? booking.discount_lines : [];
  const banks = Array.isArray(booking.payment_banks) ? booking.payment_banks : [];

  const feeTableRows = feeLines
    .map((line, index) => {
      const detail = lineDetail(line, isArabic);
      const label = lineLabel(line, isArabic);
      const suffix = detail ? ` (${detail})` : "";
      return `<tr>
        <td style="text-align:center;">${index + 1}</td>
        <td>${label}${suffix}</td>
        <td dir="ltr" style="text-align:${isArabic ? "left" : "right"};">${formatCurrency(line.amount, currency, isArabic ? "ar" : "en")}</td>
      </tr>`;
    })
    .join("");

  const discountTableRows = discountLines
    .filter((line) => !line.is_summary)
    .map((line, index) => {
      const rowNumber = feeLines.length + index + 1;
      return `<tr class="discount-row">
        <td style="text-align:center;">${rowNumber}</td>
        <td>${lineLabel(line, isArabic)}</td>
        <td dir="ltr" style="text-align:${isArabic ? "left" : "right"};">${formatCurrency(line.amount, currency, isArabic ? "ar" : "en")}</td>
      </tr>`;
    })
    .join("");

  const totalDiscountAmount =
    booking.total_discount ??
    Math.abs(Number(discountLines.find((line) => line.is_summary)?.amount || 0));

  const detailRowCount = feeLines.length > 0 ? feeLines.length : 1;
  const discountRowCount = discountLines.filter((line) => !line.is_summary).length;
  const showSummaryDiscountRow = totalDiscountAmount > 0 && discountRowCount === 0;
  const summaryDiscountRowNumber = detailRowCount + 1;

  const tableBodyRows = feeTableRows
    || `<tr><td style="text-align:center;">1</td><td>${esc(courseName || "-")}</td><td dir="ltr" style="text-align:${isArabic ? "left" : "right"};">-</td></tr>`;

  const bankCards = banks
    .map((bank, index) => {
      const name = isArabic ? bank.ar_name || bank.name : bank.name || bank.ar_name;
      const beneficiary = isArabic ? bank.ar_beneficiary || bank.beneficiary : bank.beneficiary;
      const logoSrc = inlineImages?.bankLogos?.[index] || (bank.logo_url ? absAsset(bank.logo_url) : "");
      const logoHtml = logoSrc
        ? imgTag(logoSrc, name)
        : esc(bank.logo_text || name);
      return `<div class="bank-card"><div class="bank-logo">${logoHtml}</div><div class="bank-body"><div class="bank-row"><span>${L("beneficiary", isArabic ? "اسم المستفيد" : "Beneficiary")}: ${esc(beneficiary)}</span></div><div class="bank-row"><span>${L("account", isArabic ? "رقم الحساب" : "Account")}: ${esc(bank.account_number)}</span></div><div class="bank-row"><span>IBAN: ${esc(bank.iban)}</span></div></div></div>`;
    })
    .join("");

  const contactSection = `<div class="contact-section">
      <h2 class="section-title">${L("contact_us", isArabic ? "تواصل معنا" : "Contact Us")}</h2>
      <p class="muted">${L("contact_hint", isArabic ? "إذا كنت بحاجة إلى مساعدة، يمكنك التواصل معنا عبر التفاصيل التالية" : "If you need help, you can contact us using the details below")}</p>
      <div class="contact-rows">
        <div class="contact-row"><span class="contact-label">${L("email", isArabic ? "البريد الإلكتروني" : "Email")}</span><span class="contact-value" dir="ltr">${INVOICE_CONTACT_EMAIL}</span></div>
        <div class="contact-row"><span class="contact-label">${L("whatsapp", isArabic ? "واتساب" : "WhatsApp")}</span><span class="contact-value" dir="ltr">${INVOICE_CONTACT_WHATSAPP}</span></div>
      </div>
    </div>`;

  const paymentSection = banks.length
    ? `<div class="payment-section">
        <h2 class="section-title">${L("payment_methods", isArabic ? "طرق الدفع" : "Payment Methods")}</h2>
        <p class="muted">${L("payment_hint", isArabic ? "يمكنك الدفع عن طريق الحوالة البنكية إلى أي من البنوك التالية" : "You can pay via bank transfer to any of the following banks")}</p>
        <div class="banks">${bankCards}</div>
      </div>`
    : "";

  const bodyStyle = forPdf
    ? "margin:0;padding:0;background:#fff;color:#102233;font-family:system-ui,-apple-system,Segoe UI,sans-serif"
    : "margin:0;padding:24px;background:#eef2f7;color:#102233;font-family:system-ui,-apple-system,Segoe UI,sans-serif";
  const pageStyle = forPdf
    ? "background:#fff;padding:28px 32px;width:794px;margin:0;border:none;border-radius:0;box-shadow:none;max-width:none"
    : "background:#fff;border:1px solid #e2e8f0;border-radius:24px;padding:28px 32px;max-width:920px;margin:0 auto;box-shadow:0 16px 50px rgba(15,23,42,.08)";

  return `<!doctype html>
<html lang="${lang}" dir="${dir}">
<head>
<meta charset="utf-8"/>
<title>${L("invoice_title", "Invoice")} ${esc(bookingId)}</title>
<style>
  *{box-sizing:border-box} body{${bodyStyle}}
  .page{${pageStyle}}
  .header{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:22px}
  .logos{display:flex;align-items:center;gap:16px}
  .logos img{height:44px;max-width:140px;object-fit:contain}
  .logos .divider{width:1px;height:36px;background:#cbd5e1}
  .title{font-size:18px;font-weight:800;margin:0 0 4px}
  .sub{color:#64748b;font-size:12px;margin:0}
  .copy-side{text-align:${isArabic ? "left" : "right"}}
  .badge{display:inline-flex;padding:8px 14px;background:#eef5ff;color:#0f6cc8;border-radius:12px;font-weight:700;font-size:13px;margin-top:8px}
  .summary{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:18px 0 22px}
  .card{border:1px solid #e64040;border-radius:14px;padding:12px;text-align:center}
  .card .label{color:#e64040;font-weight:700;font-size:12px;margin:0 0 6px}
  .card .value{margin:0;font-weight:800;font-size:16px}
  table{width:100%;border-collapse:collapse;margin-top:8px;table-layout:fixed}
  th{background:#e64040;color:#fff;padding:10px 12px;font-size:12px;text-align:${isArabic ? "right" : "left"}}
  th:first-child{text-align:center;width:48px}
  th:last-child{text-align:${isArabic ? "left" : "right"};width:140px}
  td{padding:10px 12px;font-size:13px;border-bottom:1px solid #e5e7eb;vertical-align:top}
  tbody tr:nth-child(odd):not(.invoice-total) td{background:#fafbfd}
  tr.discount-row td{color:#16a34a}
  tr.invoice-total td{font-weight:800;color:#e64040;border-top:2px solid #e64040;border-bottom:0;background:#fff !important}
  tr.invoice-total td:first-child{width:48px}
  tr.invoice-total td:last-child{text-align:${isArabic ? "left" : "right"};width:140px}
  .contact-section{margin-top:24px;padding-top:20px;border-top:1px solid #e2e8f0;page-break-inside:avoid}
  .contact-rows{display:flex;flex-direction:column;gap:8px}
  .contact-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 14px;border:1px solid #e2e8f0;border-radius:12px;background:#f8fafc;font-size:13px}
  .contact-label{font-weight:700;color:#475569}
  .contact-value{font-weight:700;color:#102233}
  .payment-section{margin-top:18px;padding-top:18px;border-top:1px solid #e2e8f0;page-break-inside:avoid}
  .section-title{font-size:16px;font-weight:800;margin:0 0 10px}
  .muted{color:#64748b;font-size:12px;margin:0 0 12px;line-height:1.5}
  .banks{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
  .bank-card{border:1px solid #dbeafe;border-radius:14px;overflow:hidden;background:#fff;page-break-inside:avoid}
  .bank-logo{height:60px;display:flex;align-items:center;justify-content:center;font-weight:800;color:#1d4ed8;background:#fff;padding:8px;text-align:center;font-size:12px}
  .bank-body{background:#eff6ff;padding:10px;font-size:10px;line-height:1.6}
  @media print{
    @page{size:A4;margin:10mm}
    body{background:#fff;padding:0}
    .page{box-shadow:none;border:none;border-radius:0;margin:0;max-width:none;padding:12mm 14mm;page-break-after:avoid;page-break-inside:avoid}
    .banks{grid-template-columns:repeat(3,1fr);gap:8px}
    .bank-logo{height:52px}
  }
</style>
</head>
<body>
  <div class="page">
    <div class="header">
      <div>
        <div class="logos">
          ${imgTag(courseSatLogo, "CourseSat", "height:44px;max-width:140px;object-fit:contain")}
          ${schoolLogoSrc ? `<div class="divider"></div>${imgTag(schoolLogoSrc, schoolName, "height:44px;max-width:140px;object-fit:contain")}` : ""}
        </div>
        <p class="title" style="margin-top:14px">${L("view_invoice", "View invoice")}</p>
        <p class="sub">${esc(schoolName || "")}</p>
      </div>
      <div class="copy-side">
        <p class="title">${L("student_copy", isArabic ? "نسخة الطالب" : "Student Copy")}</p>
        <div class="badge">#${esc(bookingId)}</div>
      </div>
    </div>
    <div class="summary">
      <div class="card"><p class="label">${L("total", "Total")}</p><p class="value" dir="ltr">${formatCurrency(total, currency, isArabic ? "ar" : "en")}</p></div>
      <div class="card"><p class="label">${L("start_date", "Start Date")}</p><p class="value">${esc(formatDate(booking.start_date))}</p></div>
      <div class="card"><p class="label">${L("booking_no", "Booking #")}</p><p class="value">#${esc(bookingId)}</p></div>
    </div>
    <table>
      <thead><tr><th>#</th><th>${L("booking_summary", isArabic ? "ملخص الحجز" : "Booking Summary")}</th><th>${L("total", "Total")}</th></tr></thead>
      <tbody>
        ${tableBodyRows}
        ${discountTableRows}
        ${showSummaryDiscountRow ? `<tr class="discount-row"><td style="text-align:center;">${summaryDiscountRowNumber}</td><td>${L("total_discount", isArabic ? "اجمالي الخصم" : "Total Discount")}</td><td dir="ltr" style="text-align:${isArabic ? "left" : "right"};">${formatCurrency(-1 * totalDiscountAmount, currency, isArabic ? "ar" : "en")}</td></tr>` : ""}
        <tr class="invoice-total">
          <td></td>
          <td>${L("grand_total", isArabic ? "الاجمالي ( السعر شامل جميع الرسوم )" : "Total (includes all fees)")}</td>
          <td dir="ltr">${formatCurrency(total, currency, isArabic ? "ar" : "en")}</td>
        </tr>
      </tbody>
    </table>
    ${contactSection}
    ${paymentSection}
  </div>
</body>
</html>`;
}

export async function buildBookingInvoiceHtmlAsync(booking, options = {}) {
  const inlineImages = await prepareInlineInvoiceImages(booking);
  return buildBookingInvoiceHtml(booking, { ...options, inlineImages });
}

const A4_WIDTH_PX = 794;

function waitForDocumentImages(doc) {
  const images = Array.from(doc?.querySelectorAll("img") ?? []);
  return Promise.all(
    images.map(
      (img) =>
        new Promise((resolve) => {
          if (img.complete) {
            resolve();
            return;
          }
          img.onload = () => resolve();
          img.onerror = () => resolve();
        }),
    ),
  );
}

function waitForDocumentFonts(doc) {
  const fonts = doc?.fonts;
  if (!fonts?.ready) return Promise.resolve();
  return fonts.ready.catch(() => undefined);
}

export async function downloadBookingInvoice(booking, options = {}) {
  if (typeof window === "undefined" || !booking) return;

  const [{ jsPDF }, html2canvasModule] = await Promise.all([import("jspdf"), import("html2canvas")]);
  const html2canvas = html2canvasModule.default;
  const inlineImages = await prepareInlineInvoiceImages(booking);
  const html = buildBookingInvoiceHtml(booking, { ...options, forPdf: true, inlineImages });

  const iframe = document.createElement("iframe");
  iframe.setAttribute("aria-hidden", "true");
  iframe.style.position = "fixed";
  iframe.style.left = "-10000px";
  iframe.style.top = "0";
  iframe.style.width = `${A4_WIDTH_PX}px`;
  iframe.style.height = "1200px";
  iframe.style.border = "0";
  document.body.appendChild(iframe);

  try {
    const doc = iframe.contentDocument;
    if (!doc) return;

    doc.open();
    doc.write(html);
    doc.close();

    await waitForDocumentFonts(doc);
    await waitForDocumentImages(doc);

    const page = doc.querySelector(".page");
    if (!page) return;

    const canvas = await html2canvas(page, {
      scale: 2,
      useCORS: true,
      backgroundColor: "#ffffff",
      logging: false,
      width: A4_WIDTH_PX,
      windowWidth: A4_WIDTH_PX,
    });

    const pdf = new jsPDF({ orientation: "portrait", unit: "mm", format: "a4" });
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();
    const imgData = canvas.toDataURL("image/png");

    let renderWidth = pageWidth;
    let renderHeight = (canvas.height / canvas.width) * renderWidth;

    if (renderHeight > pageHeight) {
      renderHeight = pageHeight;
      renderWidth = (canvas.width / canvas.height) * renderHeight;
    }

    pdf.addImage(imgData, "PNG", 0, 0, renderWidth, renderHeight);

    const idPart = booking.reference_no || booking.booking_id || booking.id || "invoice";
    pdf.save(`${idPart}.pdf`);
  } finally {
    document.body.removeChild(iframe);
  }
}
