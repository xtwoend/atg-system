/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import './tabler';

import { createApp } from 'vue';


/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

// components

import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
app.component('EasyDataTable', Vue3EasyDataTable);

// datatable
import DataTable from './components/DataTable.vue';
app.component('DataTable', DataTable);

// chart
import Highcharts from 'highcharts';
import exportingInit from 'highcharts/modules/exporting';
import stockInit from 'highcharts/modules/stock';
import accessibility from 'highcharts/modules/accessibility';
exportingInit(Highcharts);
stockInit(Highcharts);
accessibility(Highcharts);

import HighchartsVue from 'highcharts-vue';
app.use(HighchartsVue);

// svg
import SvgEmbeded from './components/SvgEmbeded.vue';
app.component('svg-embeded', SvgEmbeded);

// mimic home
import SvgHomeEmbeded from './components/HomeSvgEmbeded.vue';
app.component('svg-home', SvgHomeEmbeded);

// Trend
import TrendChart from './components/TrendChart.vue';
app.component('trend-chart', TrendChart);

// Report Chart
import ReportChart from './components/ReportChart.vue';
app.component('report-chart', ReportChart);

// Chart 
import Chart from './components/Chart.vue';
app.component('chart', Chart)

// datetime
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
app.component('date-picker', DatePicker);

import DateRange from './components/DateRange.vue';
app.component('date-range', DateRange);

import DateInput from './components/DateInput.vue';
app.component('date-input', DateInput);

app.config.globalProperties.$filters = {
    queryParams(obj, prefix) {
        var str = [], k, v;
        for(var p in obj) {
            if (!obj.hasOwnProperty(p)) {continue;} // skip things from the prototype
            if (~p.indexOf('[')) {
                k = prefix ? prefix + "[" + p.substring(0, p.indexOf('[')) + "]" + p.substring(p.indexOf('[')) : p;
                // only put whatever is before the bracket into new brackets; append the rest
            } else {
                k = prefix ? prefix + "[" + p + "]" : p;
            }
            v = obj[p];
            str.push(typeof v == "object" ?
            app.config.globalProperties.$filters.queryParams(v, k) :
            encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
        return str.join("&");
    }
}
/** 
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
