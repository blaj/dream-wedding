import { Controller } from '@hotwired/stimulus';
import { TemplateUtils } from '@assets/common';
import axios from 'axios';
import Routing from 'fos-router';

export abstract class RenameController extends Controller<HTMLDivElement> {
  static targets = ['text', 'input', 'rename', 'save', 'cancel'];

  static values = { id: Number, weddingId: Number, locale: String };

  declare readonly textTarget: HTMLSpanElement;
  declare readonly inputTarget: HTMLInputElement;
  declare readonly renameTarget: HTMLElement;
  declare readonly saveTarget: HTMLElement;
  declare readonly cancelTarget: HTMLElement;

  declare readonly idValue: number;
  declare readonly weddingIdValue: number;
  declare readonly localeValue: string;

  public clickRename = (): void => {
    TemplateUtils.hide(this.renameTarget);
    TemplateUtils.show(this.inputTarget);
    TemplateUtils.hide(this.textTarget);
    TemplateUtils.show(this.saveTarget);
    TemplateUtils.show(this.cancelTarget);
  };

  public clickSave = (): void => {
    axios
      .put(
        Routing.generate(this.updateNameRoute, {
          id: this.idValue,
          weddingId: this.weddingIdValue,
          _locale: this.localeValue
        }),
        { name: this.inputTarget.value }
      )
      .then(() => {
        this.textTarget.innerText = this.inputTarget.value;

        TemplateUtils.show(this.renameTarget);
        TemplateUtils.hide(this.inputTarget);
        TemplateUtils.show(this.textTarget);
        TemplateUtils.hide(this.saveTarget);
        TemplateUtils.hide(this.cancelTarget);
      })
      .catch(() => {
        alert('Wystąpił błąd wewnętrzny serwera. Skontaktuj się z pomocą.');
      });
  };

  public clickCancel = (): void => {
    TemplateUtils.show(this.renameTarget);
    TemplateUtils.hide(this.inputTarget);
    TemplateUtils.show(this.textTarget);
    TemplateUtils.hide(this.saveTarget);
    TemplateUtils.hide(this.cancelTarget);

    this.inputTarget.value = this.textTarget.innerText;
  };

  abstract get updateNameRoute(): string;
}
