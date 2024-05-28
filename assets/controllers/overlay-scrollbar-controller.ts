import { Controller } from '@hotwired/stimulus';
import {
  OverlayScrollbars,
  ScrollbarsHidingPlugin,
  ClickScrollPlugin,
  SizeObserverPlugin
} from 'overlayscrollbars';

export default class extends Controller<HTMLElement> {
  private overlayScrollbar: OverlayScrollbars;

  initialize = (): void => {
    OverlayScrollbars.plugin([ScrollbarsHidingPlugin, ClickScrollPlugin, SizeObserverPlugin]);
  };

  connect = (): void => {
    this.overlayScrollbar = OverlayScrollbars(this.element, {
      scrollbars: {
        autoHide: 'leave',
        autoHideDelay: 200
      },
      overflow: {
        x: 'visible-hidden',
        y: 'scroll'
      }
    });
  };

  disconnect = (): void => {
    this.overlayScrollbar.destroy();
  };
}
