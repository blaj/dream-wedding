import './styles/app.scss';
import { Tooltip, Toast } from 'bootstrap';
import { startStimulusApp } from '@symfony/stimulus-bridge';

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList: Tooltip[] = [...tooltipTriggerList].map(
  (tooltipTriggerEl: Element) => new Tooltip(tooltipTriggerEl)
);

const toastElList = document.querySelectorAll('.toast');
const toastList: Toast[] = [...toastElList].map((toastEl: Element) => new Toast(toastEl));

export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));
