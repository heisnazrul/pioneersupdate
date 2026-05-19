"use client";

import { useEffect, useMemo, useState, useCallback } from "react";
import Link from "next/link";
import { buildApiUrl, formatCurrency, getImageUrl } from "@/lib/courseenglishApi";
import { useCourseEnglishSettings } from "@/lib/courseenglishSettings";

export default function StudentBookingsPage() {
  const { isArabic } = useCourseEnglishSettings();
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(true);
  const [invoiceModal, setInvoiceModal] = useState({
    open: false,
    booking: null,
    src: null,
    downloadUrl: null,
    loading: false,
    error: null,
    kind: null, // 'pdf' | 'html'
    fallbackHtml: null,
  });

  const t = useMemo(
    () =>
      isArabic
        ? {
            title: "حجوزاتي",
            subtitle: "جميع طلباتك في مكان واحد لسهولة المتابعة.",
            bookingNo: "رقم الحجز",
            startDate: "تاريخ البدء",
            total: "المبلغ الإجمالي",
            weeks: "عدد الأسابيع",
            courseName: "اسم الدورة",
            viewInvoice: "عرض الفاتورة",
            downloadInvoice: "تحميل الفاتورة",
            invoiceUnavailable: "لا توجد فاتورة متاحة لهذا الحجز حالياً.",
            empty: "لا توجد حجوزات حالياً",
            emptySub: "ابدأ بالحجز من صفحة المعاهد.",
            explore: "تصفح المعاهد",
          }
        : {
            title: "My Bookings",
            subtitle: "All your requests in one place.",
            bookingNo: "Booking #",
            startDate: "Start Date",
            total: "Total",
            weeks: "Weeks",
            courseName: "Course",
            viewInvoice: "View invoice",
            downloadInvoice: "Download PDF",
            invoiceUnavailable: "No invoice is available for this booking yet.",
            empty: "No bookings yet",
            emptySub: "Start by booking from institutes page.",
            explore: "Explore",
          },
    [isArabic]
  );

  useEffect(() => {
    let ignore = false;

    async function load() {
      const token = localStorage.getItem("auth_token");
      const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
      if (!token) {
        if (!ignore) setLoading(false);
        return;
      }

      try {
        const res = await fetch(buildApiUrl("/courseenglish/student-bookings"), {
          headers: {
            Accept: "application/json",
            Authorization: `${tokenType} ${token}`,
          },
          cache: "no-store",
        });
        const json = await res.json();
        if (!res.ok || !json?.success) throw new Error("Failed");
        if (!ignore) setBookings(Array.isArray(json?.data) ? json.data : []);
      } catch {
        if (!ignore) setBookings([]);
      } finally {
        if (!ignore) setLoading(false);
      }
    }

    load();
    return () => {
      ignore = true;
    };
  }, []);

  const closeInvoiceModal = useCallback(() => {
    setInvoiceModal((prev) => {
      if (prev.src) URL.revokeObjectURL(prev.src);
      return { open: false, booking: null, src: null, downloadUrl: null, loading: false, error: null, kind: null, fallbackHtml: null };
    });
  }, []);

  const buildFallbackInvoiceHtml = useCallback(
    (item) => {
      const currency = item?.currency || "SAR";
      const total = item?.final_price ?? item?.total ?? 0;
      const bookingId = item?.booking_id || item?.id || "-";
      const issueDate = formatDate(item?.created_at || new Date().toISOString());
      const courseName = item?.course_ar_name || item?.course_name || "-";
      const schoolName = item?.school_ar_name || item?.school_name || "-";
      const schoolLogo = pickLogo(item);
      const sarAmount = formatCurrency(total, currency);
      const gbpAmount = item?.gbp_total ? formatCurrency(item.gbp_total, "GBP") : "";

      const lineItems = buildLineItems(item, currency, courseName, total, isArabic);
      const sumSar = lineItems.reduce((acc, li) => acc + (Number(li.sar) || 0), 0);
      const sumGbp = lineItems.reduce((acc, li) => acc + (Number(li.gbp) || 0), 0);

      const labels = isArabic
        ? {
            studentCopy: "نسخة الطالب",
            booking: "رقم الحجز",
            invoice: "عرض الفاتورة",
            unconfirmed: "حجز غير مؤكد",
            invoiceDate: "تاريخ الفاتورة",
            amountTotal: "المبلغ الإجمالي",
            summary: "ملخص الحجز",
            total: "الإجمالي",
            bankName: "اسم البنك:",
            accountName: "اسم الحساب:",
            accountNumber: "رقم الحساب:",
            iban: "رقم الآيبان:",
            swift: "سوفت كود:",
          }
        : {
            studentCopy: "Student Copy",
            booking: "Booking #",
            invoice: "Invoice",
            unconfirmed: "Unconfirmed Booking",
            invoiceDate: "Invoice Date",
            amountTotal: "Total Amount",
            summary: "Booking Summary",
            total: "Total",
            bankName: "Bank Name:",
            accountName: "Account Name:",
            accountNumber: "Account Number:",
            iban: "IBAN:",
            swift: "SWIFT:",
          };

      return `<!doctype html>
<html lang="${isArabic ? "ar" : "en"}" dir="${isArabic ? "rtl" : "ltr"}">
<head>
  <meta charset="utf-8" />
  <title>${labels.invoice} ${bookingId}</title>
  <style>
    :root { --ink:#111827; --muted:#6b7280; --line:#e5e7eb; --blue:#0f6cc8; --red:#e64040; --bg:#f6f8fb; }
    * { box-sizing: border-box; }
    body { font-family: 'Graphik Arabic','Inter','Segoe UI',system-ui,-apple-system,sans-serif; margin:0; padding:28px; background: var(--bg); color: var(--ink); }
    .sheet { background:#fff; border:1px solid #e7ecf3; border-radius:20px; padding:28px 32px; max-width:980px; margin:0 auto; box-shadow:0 16px 60px rgba(15,23,42,0.12); }
    .top { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; }
    .logo { height:48px; object-fit:contain; }
    .copy { text-align:${isArabic ? "left" : "right"}; }
    .copy-title { margin:0; font-size:18px; font-weight:800; }
    .badge { display:inline-flex; align-items:center; padding:10px 14px; background:#eef5ff; color:var(--blue); border-radius:12px; font-weight:700; font-size:13px; margin-top:6px; }
    .section { margin:20px 0 10px; text-align:center; color:var(--blue); font-weight:700; }
    .summary-box { display:grid; grid-template-columns: repeat(3,1fr); gap:12px; margin:14px 0 18px; }
    .summary-card { border:1px solid var(--red); border-radius:14px; padding:12px 10px; text-align:center; }
    .summary-card .label { color:var(--red); font-weight:700; margin:0 0 6px; }
    .summary-card .value { margin:0; font-weight:700; color:var(--ink); }
    table { width:100%; border-collapse:collapse; }
    thead th { background:var(--red); color:#fff; padding:10px; font-size:12px; text-align:${isArabic ? "right" : "left"}; }
    thead th:first-child { text-align:center; }
    tbody td { padding:10px; font-size:13px; border-bottom:1px solid var(--line); }
    tbody tr:nth-child(odd) td { background:#fafbfd; }
    .totals td { font-weight:800; color:var(--red); border-bottom:0; }
    .totals td:last-child { text-align:${isArabic ? "left" : "right"}; }
    .bank { margin-top:18px; border:1px solid var(--ink); border-radius:12px; padding:14px; }
    .bank-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid var(--line); font-size:12px; }
    .bank-row:last-child { border-bottom:0; }
    .bank-label { font-weight:700; }
  </style>
</head>
<body>
  <div class="sheet">
    <div class="top">
      <div style="display:flex; align-items:flex-start; gap:12px;">
        ${schoolLogo ? `<img class="logo" src="${schoolLogo}" alt="logo" />` : ""}
        <div>
          <p style="margin:0; font-weight:700; font-size:16px;">${labels.invoice}</p>
          <p style="margin:4px 0 0; color:var(--muted); font-size:12px;">${schoolName}</p>
        </div>
      </div>
      <div class="copy">
        <p class="copy-title">${labels.studentCopy}</p>
        <div class="badge">${bookingId}</div>
      </div>
    </div>

    <div class="section">${labels.unconfirmed}</div>

    <div class="summary-box">
      <div class="summary-card"><p class="label">${labels.amountTotal}</p><p class="value">${sarAmount}</p></div>
      <div class="summary-card"><p class="label">${labels.invoiceDate}</p><p class="value">${issueDate}</p></div>
      <div class="summary-card"><p class="label">${labels.booking}</p><p class="value">${bookingId}</p></div>
    </div>

    <table>
      <thead>
        <tr>
          <th style="width:40px; text-align:center;">#</th>
          <th>${labels.summary}</th>
          <th style="width:120px; text-align:${isArabic ? "left" : "right"};">GBP</th>
          <th style="width:150px; text-align:${isArabic ? "left" : "right"};">${currency}</th>
        </tr>
      </thead>
      <tbody>
        ${lineItems
          .map(
            (svc, idx) => `<tr>
              <td style="text-align:center;">${idx + 1}</td>
              <td>${svc.name || courseName}</td>
              <td style="text-align:${isArabic ? "left" : "right"};">${svc.gbp_display || ""}</td>
              <td style="text-align:${isArabic ? "left" : "right"};">${svc.sar_display || ""}</td>
            </tr>`
          )
          .join("")}
        <tr class="totals">
          <td></td>
          <td style="text-align:${isArabic ? "right" : "left"};">${labels.total}</td>
          <td style="text-align:${isArabic ? "left" : "right"};">${gbpAmount || (sumGbp ? formatCurrency(sumGbp, "GBP") : "")}</td>
          <td style="text-align:${isArabic ? "left" : "right"};">${sarAmount || formatCurrency(sumSar, currency)}</td>
        </tr>
      </tbody>
    </table>

    <div class="bank">
      <div class="bank-row"><span class="bank-label">${labels.bankName}</span><span>${item?.bank_name || "-"}</span></div>
      <div class="bank-row"><span class="bank-label">${labels.accountName}</span><span>${item?.bank_account_name || schoolName || "-"}</span></div>
      <div class="bank-row"><span class="bank-label">${labels.accountNumber}</span><span>${item?.bank_account_number || "-"}</span></div>
      <div class="bank-row"><span class="bank-label">${labels.iban}</span><span>${item?.bank_iban || "-"}</span></div>
      <div class="bank-row"><span class="bank-label">${labels.swift}</span><span>${item?.bank_swift || "-"}</span></div>
    </div>
  </div>
</body>
</html>`;
    },
    [isArabic]
  );

  const buildInvoiceHtmlFromData = (data, isAr) => {
    const lineItems = Array.isArray(data?.line_items) ? data.line_items : [];
    const currency = data?.currency || data?.totals?.currency || "SAR";
    const total = data?.totals?.amount || 0;
    const bookingId = data?.booking_id || "";
    const courseName = data?.course?.name || "";
    const issueDate = data?.course?.start_date || "";
    const schoolName = data?.school?.name || "";
    const logo = data?.school?.logo || "";
    const labels = isAr
      ? {
          invoice: "عرض الفاتورة",
          studentCopy: "نسخة الطالب",
          unconfirmed: "حجز غير مؤكد",
          amountTotal: "المبلغ الإجمالي",
          invoiceDate: "تاريخ الفاتورة",
          booking: "رقم الحجز",
          summary: "تفاصيل الحجز",
          total: "الإجمالي",
        }
      : {
          invoice: "Invoice",
          studentCopy: "Student Copy",
          unconfirmed: "Unconfirmed Booking",
          amountTotal: "Total Amount",
          invoiceDate: "Invoice Date",
          booking: "Booking #",
          summary: "Booking Summary",
          total: "Total",
        };

    const rowsHtml = lineItems
      .map(
        (li, idx) => `<tr>
        <td style="text-align:center;">${idx + 1}</td>
        <td>${li.name ?? ""}</td>
        <td style="text-align:${isAr ? "left" : "right"};">${li.gbp_display || ""}</td>
        <td style="text-align:${isAr ? "left" : "right"};">${li.sar_display || li.total || ""}</td>
      </tr>`
      )
      .join("");

    return `<!doctype html>
<html lang="${isAr ? "ar" : "en"}" dir="${isAr ? "rtl" : "ltr"}">
<head>
  <meta charset="utf-8" />
  <style>
    :root { --ink:#111827; --muted:#6b7280; --line:#e5e7eb; --blue:#0f6cc8; --red:#e64040; --bg:#f6f8fb; }
    * { box-sizing: border-box; }
    body { font-family: 'Graphik Arabic','Inter','Segoe UI',system-ui,-apple-system,sans-serif; margin:0; padding:28px; background: var(--bg); color: var(--ink); }
    .sheet { background:#fff; border:1px solid #e7ecf3; border-radius:20px; padding:28px 32px; max-width:980px; margin:0 auto; box-shadow:0 16px 60px rgba(15,23,42,0.12); }
    .top { display:flex; justify-content:space-between; align-items:flex-start; gap:16px; }
    .logo { height:48px; object-fit:contain; }
    .copy { text-align:${isAr ? "left" : "right"}; }
    .copy-title { margin:0; font-size:18px; font-weight:800; }
    .badge { display:inline-flex; align-items:center; padding:10px 14px; background:#eef5ff; color:var(--blue); border-radius:12px; font-weight:700; font-size:13px; margin-top:6px; }
    .section { margin:20px 0 10px; text-align:center; color:var(--blue); font-weight:700; }
    .summary-box { display:grid; grid-template-columns: repeat(3,1fr); gap:12px; margin:14px 0 18px; }
    .summary-card { border:1px solid var(--red); border-radius:14px; padding:12px 10px; text-align:center; }
    .summary-card .label { color:var(--red); font-weight:700; margin:0 0 6px; }
    .summary-card .value { margin:0; font-weight:700; color:var(--ink); }
    table { width:100%; border-collapse:collapse; }
    thead th { background:var(--red); color:#fff; padding:10px; font-size:12px; text-align:${isAr ? "right" : "left"}; }
    thead th:first-child { text-align:center; }
    tbody td { padding:10px; font-size:13px; border-bottom:1px solid var(--line); }
    tbody tr:nth-child(odd) td { background:#fafbfd; }
    .totals td { font-weight:800; color:var(--red); border-bottom:0; }
    .totals td:last-child { text-align:${isAr ? "left" : "right"}; }
  </style>
</head>
<body>
  <div class="sheet">
    <div class="top">
      <div style="display:flex; align-items:flex-start; gap:12px;">
        ${logo ? `<img class="logo" src="${logo}" alt="logo" />` : ""}
        <div>
          <p style="margin:0; font-weight:700; font-size:16px;">${labels.invoice}</p>
          <p style="margin:4px 0 0; color:var(--muted); font-size:12px;">${schoolName || ""}</p>
        </div>
      </div>
      <div class="copy">
        <p class="copy-title">${labels.studentCopy}</p>
        <div class="badge">${bookingId}</div>
      </div>
    </div>

    <div class="section">${labels.unconfirmed}</div>

    <div class="summary-box">
      <div class="summary-card"><p class="label">${labels.amountTotal}</p><p class="value">${formatCurrency(total, currency)}</p></div>
      <div class="summary-card"><p class="label">${labels.invoiceDate}</p><p class="value">${issueDate}</p></div>
      <div class="summary-card"><p class="label">${labels.booking}</p><p class="value">${bookingId}</p></div>
    </div>

    <table>
      <thead>
        <tr>
          <th style="width:40px; text-align:center;">#</th>
          <th>${labels.summary}</th>
          <th style="width:120px; text-align:${isAr ? "left" : "right"};">GBP</th>
          <th style="width:150px; text-align:${isAr ? "left" : "right"};">${currency}</th>
        </tr>
      </thead>
      <tbody>
        ${rowsHtml}
        <tr class="totals">
          <td></td>
          <td style="text-align:${isAr ? "right" : "left"};">${labels.total}</td>
          <td></td>
          <td style="text-align:${isAr ? "left" : "right"};">${formatCurrency(total, currency)}</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>
</html>`;
  };


  const resolveInvoiceUrl = (item) => {
    const candidates = [
      item?.invoice_pdf,
      item?.invoice_pdf_url,
      item?.invoice_url,
      item?.invoice,
      item?.pdf_url,
      item?.invoice_link,
    ].filter(Boolean);

    if (candidates.length > 0) {
      const url = candidates.find((u) => typeof u === "string" && u.trim().length > 0);
      if (url) return buildApiUrl(url);
    }

    if (item?.id || item?.booking_id) {
      return buildApiUrl(`/courseenglish/student-bookings/${item.booking_id || item.id}/invoice`);
    }

    return null;
  };

  const handleOpenInvoice = useCallback(
    async (item) => {
      setInvoiceModal({ open: true, booking: item, src: null, downloadUrl: null, loading: true, error: null, kind: null, fallbackHtml: null });

      const token = localStorage.getItem("auth_token");
      const tokenType = localStorage.getItem("auth_token_type") || "Bearer";
      const lang = isArabic ? "ar" : "en";

      // Try JSON invoice-data first
      try {
        const res = await fetch(buildApiUrl(`/courseenglish/student-bookings/${item.id}/invoice-data?lang=${lang}`), {
          headers: {
            Accept: "application/json",
            ...(token ? { Authorization: `${tokenType} ${token}` } : {}),
          },
        });
        if (res.ok) {
          const json = await res.json();
          if (json?.success && json?.data?.line_items?.length) {
            const html = buildInvoiceHtmlFromData(json.data, isArabic);
            const blob = new Blob([html], { type: "text/html" });
            const url = URL.createObjectURL(blob);
            setInvoiceModal((prev) => ({ ...prev, src: url, downloadUrl: url, loading: false, kind: "html", fallbackHtml: html }));
            return;
          }
        }
      } catch (e) {
        // fall through to pdf/fallback
      }

      // Fallback to PDF if available
      const invoiceUrl = resolveInvoiceUrl(item);
      if (invoiceUrl) {
        try {
          const res = await fetch(invoiceUrl, {
            headers: {
              ...(token ? { Authorization: `${tokenType} ${token}` } : {}),
              Accept: "application/pdf",
            },
          });

          if (res.ok) {
            const blob = await res.blob();
            const url = URL.createObjectURL(blob);
            setInvoiceModal((prev) => ({ ...prev, src: url, loading: false, kind: "pdf" }));
            return;
          }
        } catch (err) {
          // continue to local fallback
        }
      }

      // Final fallback: build local HTML from booking record
      const html = buildFallbackInvoiceHtml(item);
      const blob = new Blob([html], { type: "text/html" });
      const url = URL.createObjectURL(blob);
      setInvoiceModal((prev) => ({
        ...prev,
        src: url,
        downloadUrl: url,
        loading: false,
        error: null,
        kind: "html",
        fallbackHtml: html,
      }));
    },
    [buildFallbackInvoiceHtml, isArabic]
  );

  const handleDownloadInvoice = useCallback(() => {
    setInvoiceModal((prev) => {
      const idPart = prev.booking?.booking_id || prev.booking?.id || "invoice";
      const isHtml = prev.kind === "html";
      const filename = isHtml ? `${idPart}.html` : `${idPart}.pdf`;

      if (isHtml && prev.fallbackHtml) {
        const win = window.open("", "_blank");
        if (win) {
          win.document.write(prev.fallbackHtml);
          win.document.close();
          win.focus();
          win.print();
        }
      }

      if (prev.src) {
        const a = document.createElement("a");
        a.href = prev.src;
        a.download = filename;
        a.click();
      } else if (prev.downloadUrl) {
        const a = document.createElement("a");
        a.href = prev.downloadUrl;
        a.target = "_blank";
        a.rel = "noopener noreferrer";
        a.download = filename;
        a.click();
      }

      return prev;
    });
  }, []);

  if (loading) {
    return <div className="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-500">...</div>;
  }

  return (
    <div className="space-y-6">
      <section className="">
        <h1 className="text-2xl font-medium leading-[1.1] text-[#102233] md:text-4xl">{t.title}</h1>
        <p className="mt-3 text-[15px] text-slate-500 md:text-lg">{t.subtitle}</p>
      </section>

      {bookings.length === 0 ? (
        <div className="rounded-3xl border border-slate-200 bg-white p-10 text-center">
          <h3 className="text-[30px] font-semibold text-[#102233]">{t.empty}</h3>
          <p className="mt-2 text-[18px] text-slate-500">{t.emptySub}</p>
          <Link href="/language-institutes" className="mt-5 inline-block rounded-2xl bg-[#1277BE] px-8 py-4 text-[18px] font-medium text-white">
            {t.explore}
          </Link>
        </div>
      ) : (
        <div className="space-y-5">
          {bookings.map((item) => (
            <article key={item.id} className="rounded-3xl border border-slate-100 shadow-sm bg-white p-5 md:p-6 mb-5">
              {/* Top Header */}
              <div className="mb-4 flex flex-wrap items-center justify-between text-[13px] md:text-[14px]">
                <div className="flex items-center gap-1 font-medium text-[#102233]">
                  <span>{t.bookingNo}</span>
                  <span dir="ltr">#{item?.booking_id || item?.id}</span>
                </div>
                <div className="flex items-center gap-1 mt-1 md:mt-0">
                  <span className="text-[#a0aaba] font-normal">{t.startDate} : </span>
                  <span className="font-medium text-[#102233]" dir="ltr">{formatDate(item?.start_date)}</span>
                </div>
              </div>

              {/* Bottom Section: 2x2 Grid Layout */}
              <div className="grid grid-cols-[auto_1fr] gap-x-3 gap-y-3 md:gap-x-4 border-t border-slate-100 pt-5">

                {/* Row 1, Col 1: Image */}
                <div className="h-[90px] w-[90px] md:h-[110px] md:w-[110px]">
                  <img
                    src={pickBookingImage(item)}
                    alt={item?.course_name || "booking"}
                    className="h-full w-full rounded-2xl object-cover shadow-sm"
                    loading="lazy"
                    onError={(e) => {
                      e.currentTarget.onerror = null;
                      e.currentTarget.src = "/assets/hero.png";
                    }}
                  />
                </div>

                {/* Row 1, Col 2: Content Details */}
                <div className="flex flex-col justify-start text-start h-full">
                  <h3 className="text-[14px] font-semibold text-[#102233] md:text-[17px] leading-tight mb-1.5 md:mb-2 line-clamp-2">
                    {item?.school_ar_name || "-"} - {item?.school_name || "-"}
                  </h3>
                  <div className="flex flex-col gap-1 md:gap-1.5">
                    <p className="flex items-center gap-1 text-[12px] md:text-[13px] text-[#a0aaba]">
                      <span>{t.courseName} :</span>
                      <span className="font-medium text-[#102233]">{item?.course_ar_name || "-"}</span>
                    </p>
                    <p className="flex items-center gap-1 text-[12px] md:text-[13px] text-[#a0aaba]">
                      <span>{t.weeks} :</span>
                      <span className="font-medium text-[#102233]">{item?.weeks || "-"}</span>
                    </p>
                  </div>
                </div>

                {/* Row 2, Col 1: Price */}
                <div className="flex flex-col justify-end text-center md:text-start pt-1">
                  <p className="text-[11px] text-[#a0aaba] md:text-[12px] mb-0.5">{t.total}</p>
                  <p className="text-[15px] font-semibold text-[#102233] md:text-[18px] leading-none whitespace-nowrap" dir="ltr">
                    {formatCurrency(item?.final_price || item?.total || 0, item?.currency || "SAR")}
                  </p>
                </div>

                {/* Row 2, Col 2: View invoice */}
                <div className="flex items-end justify-end pb-1">
                  <button
                    type="button"
                    onClick={() => handleOpenInvoice(item)}
                    className="inline-flex items-center gap-2 rounded-xl bg-[#1277BE] px-4 py-2 text-[12px] font-medium text-white transition hover:bg-[#0e5f97]"
                  >
                    <i className="fa-regular fa-file-pdf text-[13px]" />
                    {t.viewInvoice}
                  </button>
                </div>

              </div>
            </article>
          ))}
        </div>
      )}

      {invoiceModal.open && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4 backdrop-blur-sm">
          <div className="relative w-full max-w-5xl max-h-[92vh] rounded-3xl bg-white shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div className="flex items-center justify-between px-5 py-4 border-b border-slate-100">
              <div className="flex flex-col">
                <span className="text-sm font-normal text-slate-500">
                  {t.bookingNo} #{invoiceModal.booking?.booking_id || invoiceModal.booking?.id}
                </span>
                <span className="text-lg font-medium text-[#102233]">{t.viewInvoice}</span>
              </div>
              <div className="flex items-center gap-2">
                <button
                  type="button"
                  onClick={handleDownloadInvoice}
                  className="inline-flex items-center gap-2 rounded-xl bg-[#1277BE] px-3 py-2 text-[12px] font-medium text-white transition hover:bg-[#0e5f97]"
                  disabled={invoiceModal.loading}
                >
                  <i className="fa-solid fa-download text-[13px]" />
                  {t.downloadInvoice}
                </button>
                <button
                  type="button"
                  onClick={closeInvoiceModal}
                  className="h-9 w-9 inline-flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200"
                >
                  <i className="fa-solid fa-xmark" />
                </button>
              </div>
            </div>

            <div className="flex-1 bg-slate-50 overflow-hidden">
              {invoiceModal.loading && (
                <div className="flex h-full items-center justify-center text-slate-500 text-sm">Loading invoice...</div>
              )}
              {!invoiceModal.loading && invoiceModal.error && (
                <div className="flex h-full items-center justify-center text-slate-500 text-sm px-6 text-center">
                  {invoiceModal.error}
                </div>
              )}
              {!invoiceModal.loading && !invoiceModal.error && invoiceModal.src && (
                <iframe
                  title="Invoice"
                  src={invoiceModal.kind === "pdf" ? invoiceModal.src : undefined}
                  srcDoc={invoiceModal.kind === "html" ? invoiceModal.fallbackHtml : undefined}
                  className="w-full"
                  style={{ height: "78vh", border: "none" }}
                />
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

function buildLineItems(item, currency, fallbackName, fallbackSarTotal, isArabic) {
  const lines = [];

  const label = (en, ar) => (isArabic ? ar : en);

  const pushLine = (name, sar, gbp) => {
    if (sar === undefined && gbp === undefined) return;
    const sarNum = Number(sar);
    const gbpNum = Number(gbp);
    lines.push({
      name,
      sar: sarNum,
      gbp: gbpNum,
      sar_display: sarNum || sarNum === 0 ? formatCurrency(sarNum, currency) : "",
      gbp_display: gbpNum || gbpNum === 0 ? formatCurrency(gbpNum, "GBP") : "",
    });
  };

  const arrayCandidates = [
    item?.services,
    item?.line_items,
    item?.items,
    item?.fees,
    item?.fee_items,
    item?.breakdown,
    item?.invoice_items,
    item?.invoice_lines,
  ];

  arrayCandidates.forEach((arr) => {
    if (Array.isArray(arr)) {
      arr.forEach((svc) => {
        pushLine(
          svc?.ar_name && isArabic ? svc.ar_name : svc.name || svc.description || fallbackName,
          svc.sar ?? svc.sar_total ?? svc.total ?? svc.amount ?? svc.price ?? svc.cost_sar,
          svc.gbp ?? svc.gbp_total ?? svc.amount_gbp ?? svc.price_gbp ?? svc.cost_gbp
        );
      });
    }
  });

  // Common fee fields
  pushLine(
    label("Registration fee", "رسوم التسجيل"),
    item?.registration_fee ?? item?.reg_fee
  );
  pushLine(label("Material fee", "رسوم المواد"), item?.material_fee ?? item?.materials_fee);
  pushLine(
    label("Accommodation", "السكن"),
    item?.accommodation_fee ?? item?.accommodation_price ?? item?.accommodation_total
  );
  pushLine(
    label("Airport pickup", "استقبال المطار"),
    item?.airport_pickup_fee ?? item?.pickup_fee ?? item?.airport_fee
  );
  pushLine(label("Insurance", "التأمين"), item?.insurance_fee ?? item?.insurance_price);
  pushLine(label("Other fees", "رسوم أخرى"), item?.other_fees);

  // Discounts (negative)
  if (item?.discount || item?.discount_amount) {
    const d = item.discount_amount ?? item.discount ?? 0;
    pushLine(label("Discount", "خصم"), -Math.abs(d));
  }
  if (Array.isArray(item?.discounts)) {
    item.discounts.forEach((d) =>
      pushLine(d?.ar_name && isArabic ? d.ar_name : d.name || label("Discount", "خصم"), -(Number(d.amount) || 0))
    );
  }

  // VAT / Tax
  if (item?.vat || item?.tax) {
    pushLine(label("VAT / Tax", "ضريبة"), item.vat ?? item.tax);
  }

  // Fallback single line if still empty
  if (!lines.length) {
    pushLine(fallbackName, fallbackSarTotal, item?.gbp_total);
  }

  return lines;
}

function normalizeImageUrl(value) {
  if (!value || typeof value !== "string") return "";
  const raw = value.trim();
  if (!raw) return "";
  if (/\/storage\/[a-z]$/i.test(raw)) return "";
  if (raw.startsWith("http://") || raw.startsWith("https://")) return raw;
  if (raw.startsWith("data:")) return raw;
  if (raw.startsWith("/")) return getImageUrl(raw) || "";
  if (raw.startsWith("storage/")) return getImageUrl(`/${raw}`) || "";
  if (raw.startsWith("assets/")) return `/${raw}`;
  return getImageUrl(`/storage/${raw}`) || "";
}

function pickLogo(item) {
  const candidates = [item?.school_logo, item?.logo, item?.course?.logo];
  for (const src of candidates) {
    const url = normalizeImageUrl(src);
    if (url) return url;
  }
  return "";
}

function pickBookingImage(item) {
  const candidates = [
    item?.course_image,
    item?.school_image,
    item?.school_logo,
    item?.branch_image,
    item?.image,
    item?.thumbnail,
    item?.logo,
    item?.gallery_image,
    item?.gallery_url,
    item?.course?.image,
    item?.course?.thumbnail,
    item?.course?.logo,
  ];
  for (const src of candidates) {
    const url = normalizeImageUrl(src);
    if (url) return url;
  }
  return "/assets/hero.png";
}

function formatDate(v) {
  if (!v) return "-";
  const d = new Date(v);
  if (Number.isNaN(d.getTime())) return v;
  return d.toLocaleDateString("en-CA").replace(/-/g, "-");
}
