import { Controller } from '@hotwired/stimulus';
import { CountUp } from 'countup.js';

export default class extends Controller<HTMLElement> {
  static values = {
    start: { type: Number, default: 0 },
    end: Number,
    duration: { type: Number, default: 2 }
  };

  declare readonly startValue: number;
  declare readonly endValue: number;
  declare readonly durationValue: number;

  private countUp: CountUp;

  initialize = (): void => {
    this.countUp = new CountUp(this.element, this.endValue, {
      startVal: this.startValue,
      duration: this.durationValue
    });
  };

  connect = (): void => {
    this.countUp.start();
  };
}
