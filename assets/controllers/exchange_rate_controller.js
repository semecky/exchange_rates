// assets/controllers/exchange_rate_controller.js

import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

     routes = {
        getCurrent : '/exchange_rates/{datePlaceholder}' // welp, i should've auto-wire routes here, but that's enough for the MVP
    }

    static targets = [
        'datepicker',
        'button',
        'filters',
        'sorts',
        'output',
    ]

    connect() {
        this.picker = datepicker(
            this.datepickerTarget,
            {
                dateSelected: new Date(),
                formatter: (input, date) => {
                    input.value = date.toLocaleDateString()
                }
            }
        );
        this.getCurrent();
    }
    getCurrent() {
        this.get()
    }
    get() {
        fetch(
            this.routes.getCurrent.replace('{datePlaceholder}', this.datepickerTarget.value)
        )
            .then(response => response.text())
            .then(json=>this.outputTarget.innerHTML = json)

    }
}