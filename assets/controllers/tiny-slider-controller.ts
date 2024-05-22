import { Controller } from '@hotwired/stimulus';
import { tns, TinySliderInstance } from 'tiny-slider';

export default class extends Controller<HTMLElement> {
  static values = {
    mode: { type: String, default: 'carousel' },
    axis: { type: String, default: 'horizontal' },
    gutter: { type: Number, default: 30 },
    edgePadding: { type: Number, default: 2 },
    speed: { type: Number, default: 500 },
    autoWidth: { type: Boolean, default: false },
    autoplay: { type: Boolean, default: true },
    autoplayTimeout: { type: Number, default: 4000 },
    autoplayHoverPause: { type: Boolean, default: true },
    loop: { type: Boolean, default: true },
    rewind: { type: Boolean, default: true },
    autoHeight: { type: Boolean, default: false },
    fixedWidth: { type: Boolean, default: false },
    touch: { type: Boolean, default: true },
    mouseDrag: { type: Boolean, default: true },
    items: { type: Number, default: 4 },
    itemsXl: { type: Number, default: 3 },
    itemsLg: { type: Number, default: 2 },
    itemsMd: { type: Number, default: 2 },
    itemsSm: { type: Number, default: 1 },
    itemsXs: { type: Number, default: 1 }
  };

  declare readonly modeValue: 'carousel' | 'gallery';
  declare readonly axisValue: 'horizontal' | 'vertical';
  declare readonly gutterValue: number;
  declare readonly edgePaddingValue: number;
  declare readonly speedValue: number;
  declare readonly autoWidthValue: boolean;
  declare readonly autoplayValue: boolean;
  declare readonly autoplayTimeoutValue: number;
  declare readonly autoplayHoverPauseValue: boolean;
  declare readonly loopValue: boolean;
  declare readonly rewindValue: boolean;
  declare readonly autoHeightValue: boolean;
  declare readonly fixedWidthValue: false;
  declare readonly touchValue: boolean;
  declare readonly mouseDragValue: boolean;
  declare readonly itemsValue: number;
  declare readonly itemsXlValue: number;
  declare readonly itemsLgValue: number;
  declare readonly itemsMdValue: number;
  declare readonly itemsSmValue: number;
  declare readonly itemsXsValue: number;

  private tinySlider: TinySliderInstance;

  initialize = (): void => {
    this.tinySlider = tns({
      container: this.element,
      mode: this.modeValue,
      axis: this.axisValue,
      gutter: this.gutterValue,
      edgePadding: this.edgePaddingValue,
      speed: this.speedValue,
      autoWidth: this.autoWidthValue,
      controls: true,
      nav: false,
      autoplay: this.autoplayValue,
      autoplayTimeout: this.autoplayTimeoutValue,
      autoplayHoverPause: this.autoplayHoverPauseValue,
      autoplayButton: false,
      autoplayButtonOutput: false,
      navPosition: 'top',
      controlsText: ['<i class="bi bi-arrow-left"></i>', '<i class="bi bi-arrow-right"></i>'],
      loop: this.loopValue,
      rewind: this.rewindValue,
      autoHeight: this.autoHeightValue,
      fixedWidth: this.fixedWidthValue,
      touch: this.touchValue,
      mouseDrag: this.mouseDragValue,
      arrowKeys: true,
      items: this.itemsValue,
      responsive: {
        0: {
          items: this.itemsXsValue
        },
        576: {
          items: this.itemsSmValue
        },
        768: {
          items: this.itemsMdValue
        },
        992: {
          items: this.itemsLgValue
        },
        1200: {
          items: this.itemsXlValue
        }
      }
    });
  };
}
