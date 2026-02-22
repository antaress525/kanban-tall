import './bootstrap';
import EmblaCarousel from 'embla-carousel';

const wrapper = document.querySelector('.embla')
const viewport = wrapper.querySelector('.embla__viewport')
new EmblaCarousel(viewport, {
    loop: false,
    align: 'start',
    dragFree: false,
})

document.addEventListener('alpine:init', () => {
    Alpine.data('datePicker', (config = {}) => ({
        // property exposed at x-model / wire:model
        modelValue: config.value ?? null,
        disablePast: config.disablePast ?? false,

        open: false,
        today: null,
        selected: null,
        month: null,
        year: null,
        calendar: [],
        formatted: '',

        months: [
            'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin',
            'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'
        ],

        init() {
            if (this.modelValue) {
                this.selected = new Date(this.modelValue);
            }

            const base = this.selected ?? new Date();
            this.month = base.getMonth();
            this.year = base.getFullYear();

            this.generate();
            this.format();

            this.today = new Date();
            this.today.setHours(0,0,0,0);

            // Sync if Livewire changes the value
            this.$watch('modelValue', (value) => {
                if (!value) {
                    this.selected = null;
                    this.formatted = '';
                    return;
                }


                this.selected = new Date(value);
                this.month = this.selected.getMonth();
                this.year = this.selected.getFullYear();

                this.generate();
                this.format();
            });
        },

        generate() {
            const firstDay = new Date(this.year, this.month, 1);
            const lastDay = new Date(this.year, this.month + 1, 0);

            const startDay = (firstDay.getDay() + 6) % 7;
            const daysInMonth = lastDay.getDate();

            this.calendar = [];

            const pushDay = (date, currentMonth) => {
                const normalized = new Date(date);
                normalized.setHours(0,0,0,0);

                this.calendar.push({
                    date: date.toISOString(),
                    day: date.getDate(),
                    currentMonth,
                    isDisabled: this.disablePast && normalized < this.today
                });
            };

            // jours précédent
            for (let i = startDay; i > 0; i--) {
                const d = new Date(this.year, this.month, 1 - i);
                pushDay(d, false);
            }

            // jours courant
            for (let i = 1; i <= daysInMonth; i++) {
                const d = new Date(this.year, this.month, i);
                pushDay(d, true);
            }

            while (this.calendar.length % 7 !== 0) {
                const d = new Date(
                    this.year,
                    this.month + 1,
                    this.calendar.length - daysInMonth - startDay + 1
                );
                pushDay(d, false);
            }
        },

        select(day) {
            if (day.isDisabled) return;

            this.selected = new Date(day.date);
            this.modelValue = this.selected.toISOString().split('T')[0];

            this.format();
            this.open = false;
        },

        isSelected(day) {
            if (!this.selected) return false;

            return new Date(day.date).toDateString() ===
                   this.selected.toDateString();
        },

        prevMonth() {
            this.month = this.month === 0 ? 11 : this.month - 1;
            if (this.month === 11) this.year--;

            this.generate();
        },

        nextMonth() {
            this.month = this.month === 11 ? 0 : this.month + 1;
            if (this.month === 0) this.year++;

            this.generate();
        },

        get monthLabel() {
            return `${this.months[this.month]} ${this.year}`;
        },

        format() {
            if (!this.selected) {
                this.formatted = '';
                return;
            }

            const day = this.selected.getDate();
            const month = this.months[this.selected.getMonth()];
            const year = this.selected.getFullYear();

            this.formatted = `${day} ${month} ${year}`;
        }
    }));
});