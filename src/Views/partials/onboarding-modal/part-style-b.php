<style>
/* Icon wrap */
.onboard-step__icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
}
.onboard-step__icon-wrap--cyan   { background: rgba(57, 197, 255, 0.1);  border: 1px solid rgba(57, 197, 255, 0.22); }
.onboard-step__icon-wrap--green  { background: rgba(113, 239, 192, 0.1); border: 1px solid rgba(113, 239, 192, 0.22); }
.onboard-step__icon-wrap--orange { background: rgba(255, 179, 92, 0.1);  border: 1px solid rgba(255, 179, 92, 0.22); }
.onboard-step__icon-wrap--purple { background: rgba(192, 132, 252, 0.1); border: 1px solid rgba(192, 132, 252, 0.22); }

/* Step number + title */
.onboard-step__meta { text-align: center; margin-bottom: 0.85rem; }
.onboard-step__num {
    display: block;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--color-dim);
    margin-bottom: 0.3rem;
}
.onboard-step__title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
}

/* Description */
.onboard-step__desc {
    font-size: 0.875rem;
    color: var(--color-muted);
    line-height: 1.65;
    text-align: center;
    margin-bottom: 1rem;
}
.onboard-step__desc strong { color: var(--color-text); }

/* Step 1: hint link */
.onboard-step__hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--color-brand);
    text-decoration: none;
    border: 1px solid rgba(57, 197, 255, 0.28);
    background: rgba(57, 197, 255, 0.07);
    padding: 0.35rem 0.75rem;
    border-radius: var(--radius-pill);
    width: fit-content;
    margin: 0 auto;
    transition: background 0.18s, border-color 0.18s;
}
.onboard-step__hint:hover {
    background: rgba(57, 197, 255, 0.14);
    border-color: rgba(57, 197, 255, 0.5);
}

/* Step 2: info note */
.onboard-step__note {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--color-danger-text);
    background: rgba(255, 97, 119, 0.08);
    border: 1px solid rgba(255, 97, 119, 0.25);
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    margin: 0;
}
.onboard-step__note svg { flex-shrink: 0; opacity: 0.8; }

/* Step 3: file type chips */
.onboard-step__file-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
}
.onboard-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.3rem 0.65rem;
    border-radius: var(--radius-pill);
}
.onboard-chip--photo  { background: rgba(57, 197, 255, 0.1);  color: #39c5ff;  border: 1px solid rgba(57, 197, 255, 0.22); }
.onboard-chip--audio  { background: rgba(113, 239, 192, 0.1); color: #71efc0;  border: 1px solid rgba(113, 239, 192, 0.22); }
.onboard-chip--pdf    { background: rgba(255, 179, 92, 0.1);  color: #ffb35c;  border: 1px solid rgba(255, 179, 92, 0.22); }
.onboard-chip--video  { background: rgba(192, 132, 252, 0.1); color: #c084fc;  border: 1px solid rgba(192, 132, 252, 0.22); }

/* Step 4: metadata tag pills */
.onboard-step__meta-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    justify-content: center;
}
.onboard-step__meta-tags span {
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--color-muted);
    background: rgba(121, 166, 255, 0.07);
    border: 1px solid rgba(121, 166, 255, 0.14);
    border-radius: var(--radius-pill);
    padding: 0.25rem 0.6rem;
}

/* Nav */
.onboard-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--color-line);
    gap: 0.75rem;
}
.onboard-nav .button { min-width: 90px; }
.onboard-nav .button:disabled,
.onboard-nav .button[disabled] {
    opacity: 0.35;
    pointer-events: none;
}

/* Mobile: bottom-sheet style */
@media (max-width: 540px) {
    .onboard-modal {
        padding: 0;
        align-items: flex-end;
    }
    .onboard-modal__panel {
        width: 100%;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        max-height: 88vh;
        padding: 1.5rem 1.25rem 1.25rem;
    }
    .onboard-step__icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        margin-bottom: 1rem;
    }
    .onboard-modal__title {
        font-size: 1.05rem;
        margin-bottom: 1rem;
    }
    .onboard-steps { min-height: 210px; }
    .onboard-step__title { font-size: 1rem; }
    .onboard-step__desc { font-size: 0.82rem; }
    .onboard-nav { margin-top: 1.25rem; padding-top: 0.85rem; }
}

@media (prefers-reduced-motion: reduce) {
    .onboard-step.is-active { animation: none; }
    .onboard-dot { transition: none; }
}
</style>
