<template>
    <div>
        <chart ref="chart" :constructor-type="'stockChart'" :options="options"></chart>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Highcharts from "highcharts";
import { Chart } from 'highcharts-vue'

const props = defineProps({
    startFrom: String,
    data: Array
})

const options = ref({
    title: {
        text: ''
    },
    xAxis: {
        type: 'datetime',
        labels: {
            formatter: function () {
                return Highcharts.dateFormat('%d %b %Y', this.value);
            }
        }
    },
    rangeSelector: {
        selected: 4,
    },
    series: []
})

const series = props.data.map((serie) => {
    serie.pointStart = Date.parse(props.startFrom);
    return serie;
})

options.value.series = series

</script>

<style lang="scss" scoped></style>