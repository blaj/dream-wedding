import { Controller } from '@hotwired/stimulus';
import Routing from 'fos-router';
import axios from 'axios';

export default class extends Controller<HTMLInputElement> {
  static values = {
    id: Number,
    weddingId: Number,
    locale: String
  };

  declare readonly idValue: number;
  declare readonly weddingIdValue: number;
  declare readonly localeValue: string;

  public updateCompleted = ({ target }: { target: HTMLInputElement }): void => {
    axios
      .put(
        Routing.generate('wedding_task_ajax_update_completed', {
          id: this.idValue,
          weddingId: this.weddingIdValue,
          _locale: this.localeValue
        }),
        { completed: target.checked }
      )
      .catch(() => {
        target.checked = !target.checked;

        alert('Wystąpił błąd wewnętrzny serwera. Skontaktuj się z pomocą.');
      });
  };
}
