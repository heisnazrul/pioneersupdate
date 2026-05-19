"use client";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faWhatsapp } from "@fortawesome/free-brands-svg-icons";
import { useLang } from "@/hooks/useLang";
import { useEffect, useMemo, useState } from "react";

const WHATSAPP_NUMBER = "966533875992"; // E.164 without plus
const DEFAULT_MESSAGE_EN = "Hi, I'd like to know more about studying abroad.";
const DEFAULT_MESSAGE_AR = "مرحباً، أود معرفة المزيد عن الدراسة في الخارج.";

export default function WhatsAppButton() {
  const { isAr } = useLang();
  const [showBubble, setShowBubble] = useState(false);
  const [bubbleText, setBubbleText] = useState("");
  const [typing, setTyping] = useState(false);

  const message = encodeURIComponent(isAr ? DEFAULT_MESSAGE_AR : DEFAULT_MESSAGE_EN);
  const href = `https://wa.me/${WHATSAPP_NUMBER}?text=${message}`;
  const positionClass = isAr ? "left-4 md:left-6" : "right-4 md:right-6";
  const size = 72; // px
  const iconSize = 34; // px
  const bubbleMessages = useMemo(
    () =>
      isAr
        ? ["مرحباً، كيف أساعدك؟", "تبحث عن تخصص؟", "هل تحتاج منح دراسية؟"]
        : ["Hi, how can I help you?", "Looking for a course?", "Need scholarship help?"],
    [isAr]
  );

  useEffect(() => {
    const timers: NodeJS.Timeout[] = [];
    timers.push(
      setTimeout(() => {
        setShowBubble(true);
        triggerTyping(bubbleMessages[0]);
      }, 7000)
    );
    timers.push(
      setTimeout(() => {
        let idx = 1;
        const rotate = setInterval(() => {
          triggerTyping(bubbleMessages[idx % bubbleMessages.length]);
          idx += 1;
        }, 5000);
        timers.push(rotate as unknown as NodeJS.Timeout);
      }, 11000)
    );
    timers.push(
      setTimeout(() => {
        setShowBubble(false);
      }, 24000)
    );

    return () => timers.forEach(clearTimeout);
  }, [bubbleMessages]);

  const triggerTyping = (text: string) => {
    setTyping(true);
    setBubbleText("");
    let idx = 0;
    const interval = setInterval(() => {
      idx += 1;
      setBubbleText(text.slice(0, idx));
      if (idx >= text.length) {
        setTyping(false);
        clearInterval(interval);
      }
    }, 35);
  };

  return (
    <a
      href={href}
      target="_blank"
      rel="noopener noreferrer"
      aria-label={isAr ? "تحدث معنا على واتساب" : "Chat with us on WhatsApp"}
      className={`fixed ${positionClass} bottom-5 z-50 flex items-center justify-center text-[#25D366] transition duration-200 ease-out hover:-translate-y-1 active:scale-95`}
      style={{ width: size, height: size }}
    >
      {/* Outer breathing halo */}
      <span
        className="absolute inset-[-8px] rounded-full bg-[#25d366]/18 blur-3xl animate-[pulse_3s_ease-in-out_infinite]"
        aria-hidden="true"
      ></span>
      {/* Glass bead with green border */}
      <span className="absolute inset-[3px] rounded-full bg-white/85 backdrop-blur-[3px] border-2 border-[#25d366] shadow-[0_8px_24px_rgba(0,0,0,0.08)]"></span>
      {/* Icon */}
      <FontAwesomeIcon
        icon={faWhatsapp}
        className="relative drop-shadow-[0_4px_14px_rgba(0,0,0,0.3)] text-[#25D366] animate-[float_3s_ease-in-out_infinite]"
        style={{ fontSize: iconSize, stroke: "#25D366", strokeWidth: 6 }}
      />
      <span className="sr-only">WhatsApp</span>

      {/* Teaser bubble */}
      {showBubble && (
        <div
          className={`absolute ${isAr ? "left-full ml-3" : "right-full mr-3"} bottom-2 text-sm font-semibold text-slate-800 animate-[fadeInUp_300ms_ease-out]`}
          style={{ textShadow: "0 1px 6px rgba(0,0,0,0.25)" }}
        >
          {typing ? (
            <span className="inline-flex gap-1">
              <span className="h-2 w-2 rounded-full bg-white animate-pulse"></span>
              <span className="h-2 w-2 rounded-full bg-white animate-pulse" style={{ animationDelay: "0.15s" }}></span>
              <span className="h-2 w-2 rounded-full bg-white animate-pulse" style={{ animationDelay: "0.3s" }}></span>
            </span>
          ) : (
            bubbleText
          )}
        </div>
      )}
    </a>
  );
}
