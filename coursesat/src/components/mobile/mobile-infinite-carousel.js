"use client";

import { useCallback, useEffect, useMemo, useRef } from "react";

const DEFAULT_AUTO_SCROLL_MS = 4000;
const DEFAULT_TRANSITION_MS = 500;

/**
 * Infinite carousel: advances one card at a time while keeping the next card peeking.
 * Example with 6 items on mobile (1 + peek): 1|2 → 2|3 → … → 6|1 → 1|2 …
 */
export default function MobileInfiniteCarousel({
  items,
  renderItem,
  getItemKey,
  autoScrollMs = DEFAULT_AUTO_SCROLL_MS,
  transitionMs = DEFAULT_TRANSITION_MS,
  className = "",
}) {
  const count = items.length;
  const scrollRef = useRef(null);
  const indexRef = useRef(0);
  const pausedRef = useRef(false);
  const animatingRef = useRef(false);

  const loopItems = useMemo(() => {
    if (count <= 1) {
      return items.map((item, idx) => ({ item, key: getItemKey(item, idx), origIndex: idx }));
    }
    return [
      ...items.map((item, idx) => ({ item, key: `${getItemKey(item, idx)}-a`, origIndex: idx })),
      ...items.map((item, idx) => ({ item, key: `${getItemKey(item, idx)}-b`, origIndex: idx })),
    ];
  }, [items, count, getItemKey]);

  const scrollToIndex = useCallback((targetIndex, behavior = "smooth") => {
    const container = scrollRef.current;
    if (!container) return;

    const target = container.children[targetIndex];
    if (!target) return;

    container.scrollTo({
      left: target.offsetLeft - container.clientLeft,
      behavior,
    });
  }, []);

  const advanceOne = useCallback(() => {
    if (count <= 1 || pausedRef.current || animatingRef.current) return;

    animatingRef.current = true;
    const next = indexRef.current + 1;

    if (next >= count) {
      scrollToIndex(count, "smooth");
      indexRef.current = count;

      window.setTimeout(() => {
        scrollToIndex(0, "auto");
        indexRef.current = 0;
        animatingRef.current = false;
      }, transitionMs);
      return;
    }

    scrollToIndex(next, "smooth");
    indexRef.current = next;

    window.setTimeout(() => {
      animatingRef.current = false;
    }, transitionMs);
  }, [count, scrollToIndex, transitionMs]);

  useEffect(() => {
    indexRef.current = 0;
    animatingRef.current = false;
    const container = scrollRef.current;
    if (container) container.scrollLeft = 0;
  }, [items]);

  useEffect(() => {
    if (count <= 1) return undefined;

    const interval = window.setInterval(advanceOne, autoScrollMs);
    return () => window.clearInterval(interval);
  }, [count, autoScrollMs, advanceOne]);

  const pauseAutoScroll = useCallback(() => {
    pausedRef.current = true;
    window.setTimeout(() => {
      pausedRef.current = false;
    }, autoScrollMs);
  }, [autoScrollMs]);

  const handleScroll = useCallback(() => {
    const container = scrollRef.current;
    if (!container || count <= 1 || animatingRef.current) return;

    const first = container.children[0];
    const duplicateFirst = container.children[count];
    if (!first || !duplicateFirst) return;

    const step = first.offsetWidth + 16;
    if (!step) return;

    const setWidth = step * count;
    const scrollLeft = container.scrollLeft;

    if (scrollLeft >= setWidth - 2) {
      container.scrollLeft = scrollLeft - setWidth;
      indexRef.current = 0;
    } else {
      indexRef.current = Math.round(scrollLeft / step);
    }
  }, [count]);

  if (count === 0) return null;

  return (
    <div
      ref={scrollRef}
      className={`mobile-infinite-carousel flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 py-2 scroll-smooth ${className}`}
      onScroll={handleScroll}
      onTouchStart={() => {
        pausedRef.current = true;
      }}
      onTouchEnd={pauseAutoScroll}
    >
      {loopItems.map(({ item, key, origIndex }) => renderItem(item, origIndex, key))}

      <style jsx>{`
        .mobile-infinite-carousel {
          -ms-overflow-style: none;
          scrollbar-width: none;
        }

        .mobile-infinite-carousel::-webkit-scrollbar {
          display: none;
          width: 0;
          height: 0;
        }
      `}</style>
    </div>
  );
}
