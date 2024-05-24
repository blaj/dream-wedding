import { Controller } from '@hotwired/stimulus';
import { Calendar, EventMountArg } from '@fullcalendar/core';
import plLocale from '@fullcalendar/core/locales/pl';
import enLocale from '@fullcalendar/core/locales/en-gb';
import bootstrap5Plugin from '@fullcalendar/bootstrap5';
import Routing from 'fos-router';
import { UrlConst } from '@assets/common';
import interactionPlugin from '@fullcalendar/interaction';
import { Popover } from 'bootstrap';

export default class extends Controller<HTMLDivElement> {
  static values = {
    weddingId: Number,
    locale: String
  };

  declare readonly weddingIdValue: number;
  declare readonly localeValue: string;

  protected plugins = [];
  protected initialView = 'dayGridMonth';

  private calendar: Calendar;
  private popovers: Popover[] = [];

  initialize = (): void => {
    this.calendar = new Calendar(this.element, {
      plugins: [bootstrap5Plugin, interactionPlugin, ...this.plugins],
      themeSystem: 'bootstrap5',
      initialView: this.initialView,
      locales: [plLocale, enLocale],
      locale: this.localeValue,
      editable: true,
      dayMaxEvents: true,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: ''
      },
      events:
        this.weddingIdValue !== UrlConst.nullValue
          ? Routing.generate('wedding_task_ajax_list', {
              weddingId: this.weddingIdValue,
              _locale: this.localeValue
            })
          : [],
      eventDidMount: this.onEventDidMount
    });
  };

  connect = (): void => {
    this.calendar.render();
  };

  disconnect = (): void => {
    this.calendar.destroy();
    this.popovers.forEach(popover => popover.dispose());
  };

  private onEventDidMount = (eventMountArg: EventMountArg): void => {
    this.popovers.push(
      new Popover(eventMountArg.el, {
        title: eventMountArg.event.title,
        content: eventMountArg.event.extendedProps.description,
        placement: 'top',
        trigger: 'hover'
      })
    );
  };
}
