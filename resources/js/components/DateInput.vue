<template>
    <date-picker v-model:value="value" value-type="format" format="YYYY-MM-DD" disabled-time @change="changeDate"></date-picker>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        default: Object
    },
    data () {
        return {
            value: this.default.date ?  moment(this.default.date, 'YYYY-MM-DD').format('YYYY-MM-DD') : moment().format('YYYY-MM-DD')
        }
    },
    mounted() {
        
    },
    methods: {
        changeDate(e) {
            let params = this.$filters.queryParams({
                date: e,
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