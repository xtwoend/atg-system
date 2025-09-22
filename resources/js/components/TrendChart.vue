<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <h3 class="card-title">Trend Chart</h3>

                <div class="card-actions btn-actions">
                    <div class="btn-toggle">
                        <label class="row">
                            <span class="col">Live</span>
                            <span class="col-auto">
                                <label class="form-check form-check-single form-switch">
                                    <input class="form-check-input" type="checkbox" v-model="live">
                                </label>
                            </span>
                        </label>
                    </div>
                    <a href="#" class="btn-action" @click="load()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                        </svg>
                    </a>
                    <a href="#" class="btn-action" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                            <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-popper="static">
                        <template v-for="e in headers">
                            <label class="dropdown-item" v-if="e.value !== 'terminal_time'">
                                <input class="form-check-input m-0 me-2" type="checkbox" :value="e" v-model="selected">
                                {{ e.text }}
                            </label>
                        </template>
                    </div>
                </div>
            </div>
            <highcharts ref="chart" :constructor-type="'stockChart'" :options="chartOptions"></highcharts>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        options: Object,
        url: String,
        request: Object,
        headers: Array,
        default: Array,
        socket: Object
    },
    data() {
        return {
            params: this.request,
            chartOptions: {
                xAxis: {
                    gridLineWidth: 0.5,
                    gridLineColor: '#374151'
                },
                yAxis: {
                    gridLineWidth: 0.5,
                    gridLineColor: '#374151',
                },
            },
            selected: [],
            items: this.default,
            live: false
        }
    },
    created() {
        Object.assign(this.chartOptions, this.options);
        this.default.forEach(r => this.selected.push(r))
    },
    mounted() {
        this.load()
    },
    methods: {
        async load() {
            this.$refs.chart.chart.showLoading();
            this.live = false
            let series = [];
            this.items.forEach((col, index) => {
                if (col.value !== 'terminal_time') {
                    series[index] = {
                        data: [],
                        name: col.text,
                        type: 'spline',
                        id: col.value
                    }
                }
            });

            this.chartOptions.series = series;

            let res = await axios.get(this.url, { params: this.params }).then(res => res.data);
            res.forEach((row) => {
                this.buildData(row)
            })

            this.$refs.chart.chart.hideLoading();
        },
        buildData(row){
            let time = parseInt((new Date(row.terminal_time).getTime()).toFixed(0));
            this.chartOptions.series.forEach((col, index) => {
                let val = row[col.id];
                this.chartOptions.series[index].data.push([time, val]);
            })
        },
        trendMode(mode){
            if(mode) {
                this.chartOptions.series.forEach((col, index) => {
                    this.chartOptions.series[index].data = []
                })
                this.liveInit();
            }else{
                window.Echo.leaveChannel(this.socket.channel)
                this.load()
            }
        },
        liveInit() {
            let that = this
            let channel = this.socket.channel
            let event = this.socket.event
            window.Echo.channel(channel).listen(event, (row) => {
                that.buildData(row.data)
            });
        }
    },
    watch: {
        selected(val) {
            this.items = val
            this.load()
        },
        live(val) {
            this.trendMode(val)
        }
    }
}
</script>

<style lang="scss">
.btn-toggle {
    height: 2rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    .form-switch {
        padding-left: 0px !important;
        margin-right: 10px;
    }
}
</style>