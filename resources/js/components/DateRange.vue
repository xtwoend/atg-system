<template>
    <date-picker :type="modes[mode]? modes[mode] : 'date'" v-model:value="range" value-type="format" :format="formats[mode] ? formats[mode] : 'YYYY-MM-DD'" range disabled-time @change="changeDate"></date-picker>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        default: Object,
        mode: String
    },
    data () {
        return {
            range: [],
            modes: {
                daily: 'date',
                monthly: 'month',
                yearly: 'year'
            },
            formats: {
                daily: 'YYYY-MM-DD',
                monthly: 'YYYY-MM',
                yearly: 'YYYY'
            }
        }
    },
    mounted() {
        let from = this.default.from ? moment(this.default.from, 'YYYY-MM-DD').format('YYYY-MM-DD') : moment().subtract(7, 'days').format('YYYY-MM-DD');
        let to = this.default.to ? moment(this.default.to, 'YYYY-MM-DD').format('YYYY-MM-DD') : moment().format('YYYY-MM-DD')
        this.range = [from, to]
    },
    methods: {
        changeDate(e) {
            let params = this.$filters.queryParams({
                from: e[0],
                to: e[1],
                mode: this.mode ? this.mode : 'date'
            }, '');
            window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + params;
        }
    }
}
</script>

<style lang="scss">
.mx-input {
    display: block;
    width: 100%;
    padding: 0.5625rem 0.75rem;
    font-family: var(--tblr-font-sans-serif);
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.4285714286;
    color: var(--tblr-body-color);
    appearance: none;
    background-color: var(--tblr-bg-forms);
    background-clip: padding-box;
    border: var(--tblr-border-width) solid var(--tblr-border-color);
    border-radius: var(--tblr-border-radius);
    box-shadow: var(--tblr-box-shadow-input);
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.mx-icon-calendar, .mx-icon-clear {
    color: var(--tblr-body-color);
}
</style>