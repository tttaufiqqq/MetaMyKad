<style>
.onboard-modal {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.65);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: var(--space-2);
    z-index: 70;
}
.onboard-modal.hidden { display: none; }

.onboard-modal__panel {
    width: min(700px, 100%);
    max-height: 90vh;
    overflow-y: auto;
    background: linear-gradient(160deg, rgba(15, 24, 50, 0.99), rgba(8, 14, 33, 0.98));
    border-radius: var(--radius-md);
    padding: var(--space-4) var(--space-3) var(--space-3);
    border: 1px solid var(--color-line);
    box-shadow: var(--shadow-panel);
    position: relative;
}

.onboard-modal__title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 1.25rem;
    letter-spacing: -0.01em;
}

/* Progress dots */
.onboard-dots {
    display: flex;
    gap: 5px;
    justify-content: center;
    margin-bottom: 1.75rem;
}
.onboard-dot {
    height: 6px;
    width: 6px;
    border-radius: var(--radius-pill);
    background: rgba(121, 166, 255, 0.22);
    transition: width 0.25s ease, background 0.25s ease;
    cursor: pointer;
}
.onboard-dot.is-active {
    width: 22px;
    background: var(--color-brand);
}
.onboard-dot.is-done {
    background: rgba(57, 197, 255, 0.45);
}

/* Step panels */
.onboard-steps { min-height: 260px; }
.onboard-step { display: none; }
.onboard-step.is-active {
    display: block;
    animation: onboard-in 0.28s ease;
}
@keyframes onboard-in {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
