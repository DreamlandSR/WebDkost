import $ from 'jquery';
window.$ = window.jQuery = $;

import { createPopper } from '@popperjs/core';
window.Popper = createPopper;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import 'datatables.net-bs4/css/dataTables.bootstrap4.css';
import 'datatables.net-select';


import { Chart } from 'chart.js';
window.Chart = Chart;

// Import plugin rounded bars sebagai modul ES
import roundedBarPlugin from './Chart.roundedBarCharts';
Chart.register(roundedBarPlugin);

import PerfectScrollbar from 'perfect-scrollbar';
window.PerfectScrollbar = PerfectScrollbar;

import './off-canvas';
import './hoverable-collapse';
import './template';
import './settings';
import './todolist';
import './dashboard';
import './auth-toggle';
import './pages/dashboard.js';
import './pengeluaran.js';
import './kamar.js';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
