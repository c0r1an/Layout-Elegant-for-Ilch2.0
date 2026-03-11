(function () {
    function initAccordion() {
        var form = document.getElementById('elegant-layout-settings') || document.getElementById('advsettings_form');
        if (!form || form.dataset.elegantAccordionReady === '1') {
            return;
        }

        form.dataset.elegantAccordionReady = '1';

        var sections = [];
        var cards = Array.prototype.slice.call(form.querySelectorAll('.card'));

        if (cards.length) {
            sections = cards.map(function (card) {
                var header = card.querySelector('.card-header');
                var body = card.querySelector('.card-body');

                if (!header || !body) {
                    return null;
                }

                header.classList.add('btn', 'btn-outline-secondary', 'w-100', 'text-start', 'd-flex', 'justify-content-between', 'align-items-center');
                header.setAttribute('role', 'button');
                header.setAttribute('tabindex', '0');
                header.style.cursor = 'pointer';

                var existingIndicator = header.querySelector('[data-elegant-accordion-indicator]');
                if (existingIndicator) {
                    existingIndicator.remove();
                }

                var indicator = document.createElement('span');
                indicator.setAttribute('data-elegant-accordion-indicator', '1');
                indicator.textContent = '+';
                header.appendChild(indicator);

                return {
                    header: header,
                    body: body,
                    indicator: indicator
                };
            }).filter(Boolean);
        }

        if (!sections.length) {
            var headers = Array.prototype.slice.call(form.querySelectorAll('h2'));
            if (!headers.length) {
                return;
            }

            sections = headers.map(function (header) {
                var rows = [];
                var node = header.nextElementSibling;

                while (node && node.tagName !== 'H2') {
                    if (node.classList && node.classList.contains('row') && node.classList.contains('mb-3')) {
                        rows.push(node);
                    }
                    node = node.nextElementSibling;
                }

                header.classList.add('btn', 'btn-outline-secondary', 'w-100', 'text-start', 'mb-2');
                header.setAttribute('role', 'button');
                header.setAttribute('tabindex', '0');

                var indicator = document.createElement('span');
                indicator.className = 'float-end';
                indicator.textContent = '+';
                header.appendChild(indicator);

                return {
                    header: header,
                    rows: rows,
                    indicator: indicator
                };
            });
        }

        function setOpen(indexToOpen) {
            sections.forEach(function (section, index) {
                var isOpen = index === indexToOpen;
                if (section.body) {
                    section.body.hidden = !isOpen;
                }
                if (section.rows) {
                    section.rows.forEach(function (row) {
                        row.hidden = !isOpen;
                    });
                }
                section.indicator.textContent = isOpen ? '-' : '+';
                section.header.classList.toggle('btn-secondary', isOpen);
                section.header.classList.toggle('btn-outline-secondary', !isOpen);
            });
        }

        sections.forEach(function (section, index) {
            function toggle() {
                var isOpen = section.body ? section.body.hidden === false : (section.rows.length > 0 && section.rows[0].hidden === false);
                setOpen(isOpen ? -1 : index);
            }

            section.header.addEventListener('click', toggle);
            section.header.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    toggle();
                }
            });
        });

        setOpen(0);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAccordion);
    } else {
        initAccordion();
    }
})();
