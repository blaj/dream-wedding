import './styles/app.scss';
import { Tooltip, Toast } from 'bootstrap';

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList: Tooltip[] = [...tooltipTriggerList].map(
  (tooltipTriggerEl: Element) => new Tooltip(tooltipTriggerEl)
);

const toastElList = document.querySelectorAll('.toast');
const toastList: Toast[] = [...toastElList].map((toastEl: Element) => new Toast(toastEl));
