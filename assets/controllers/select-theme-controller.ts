import { Controller } from '@hotwired/stimulus';

enum StoredTheme {
  Auto = 'Auto',
  Light = 'Light',
  Dark = 'Dark'
}

export default class extends Controller<HTMLBodyElement> {
  private storedTheme: StoredTheme;

  initialize = (): void => {
    this.storedTheme = localStorage.getItem('theme') as StoredTheme;
    this.setTheme(this.getPreferredTheme());

    const el = document.querySelector('.theme-icon-active');

    if (el != null) {
      const showActiveTheme = (theme: StoredTheme) => {
        const activeThemeIcon = document.querySelector('.theme-icon-active use');
        const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
        const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href');

        document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
          element.classList.remove('active');
        });

        btnToActive.classList.add('active');
        activeThemeIcon.setAttribute('href', svgOfActiveBtn);
      };

      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        this.setTheme(this.getPreferredTheme());
      });

      showActiveTheme(this.getPreferredTheme());

      document.querySelectorAll('[data-bs-theme-value]').forEach(toggle => {
        toggle.addEventListener('click', () => {
          const theme = toggle.getAttribute('data-bs-theme-value') as StoredTheme;
          localStorage.setItem('theme', theme);
          this.setTheme(theme);
          showActiveTheme(theme);
        });
      });
    }
  };

  private getPreferredTheme = (): StoredTheme => {
    if (this.storedTheme) {
      return this.storedTheme;
    }

    return (
      window.matchMedia('(prefers-color-scheme: dark)').matches
        ? StoredTheme.Dark
        : StoredTheme.Light
    ) as StoredTheme;
  };

  private setTheme = function (theme: StoredTheme): void {
    if (theme === StoredTheme.Auto && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.setAttribute('data-bs-theme', StoredTheme.Dark);
    } else {
      document.documentElement.setAttribute('data-bs-theme', theme);
    }
  };
}
