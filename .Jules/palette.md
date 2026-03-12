## 2024-03-12 - Added contextual aria-labels to generic links
**Learning:** Found that custom post type grids (doctors, hospitals, treatments) had repetitive generic link text ("View Profile", "Learn More"). Screen readers need context for these links.
**Action:** Used `aria-label` with `esc_attr( ... )` to inject the specific item's title/name into the link text for screen readers while keeping the visual design the same.
