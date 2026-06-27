<style>
.students-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: var(--space-3);
}

@media (max-width: 960px) {
    .students-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media (max-width: 640px) {
    .students-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

.student-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.65rem;
    padding: 1.5rem 1rem 1.25rem;
    background: linear-gradient(180deg, rgba(18, 27, 54, 0.88), rgba(8, 14, 33, 0.8));
    border: 1px solid var(--color-line);
    border-radius: var(--radius-md);
    text-decoration: none;
    text-align: center;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease, background 0.2s ease;
    animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    position: relative;
    overflow: hidden;
}

.student-card::before {
    content: "";
    position: absolute;
    inset: 0 0 auto 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(143, 235, 255, 0.4), transparent);
    pointer-events: none;
}

.student-card:hover {
    transform: translateY(-4px);
    border-color: rgba(57, 197, 255, 0.3);
    background: linear-gradient(180deg, rgba(19, 29, 58, 0.92), rgba(8, 14, 33, 0.86));
}

.student-card__photo {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(57, 197, 255, 0.22);
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(57, 197, 255, 0.08);
    flex-shrink: 0;
}

.student-card__photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.student-card__avatar {
    font-family: var(--font-heading);
    font-size: 1.65rem;
    font-weight: 800;
    color: var(--color-brand-bright);
    line-height: 1;
}

.student-card__name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.3;
    margin: 0;
}

.student-card__badge {
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-family: var(--font-mono);
    color: var(--color-secondary);
    background: rgba(113, 239, 192, 0.1);
    border: 1px solid rgba(113, 239, 192, 0.18);
    padding: 0.22rem 0.6rem;
    border-radius: var(--radius-pill);
    margin: 0;
}

.student-card--unregistered {
    opacity: 0.55;
    border-style: dashed;
}

.student-card--unregistered:hover {
    opacity: 0.85;
}

.student-card__badge--none {
    color: #ff6b6b;
    background: rgba(255, 107, 107, 0.1);
    border-color: rgba(255, 107, 107, 0.25);
}

/* Stagger student cards in rows of 4 */
.student-card:nth-child(4n+2) { animation-delay: 55ms; }
.student-card:nth-child(4n+3) { animation-delay: 110ms; }
.student-card:nth-child(4n+4) { animation-delay: 165ms; }
</style>
