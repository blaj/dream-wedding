import './styles/app.scss';
import { Tooltip } from 'bootstrap';

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList: Tooltip[] = [...tooltipTriggerList].map(
  (tooltipTriggerEl: Element) => new Tooltip(tooltipTriggerEl)
);
