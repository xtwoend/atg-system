<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <h3 class="card-title">{{ name }}</h3>
                <div class="card-actions btn-actions">
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
                        <div class="logger">
                            {{ data }}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div :id="containerId" class="scada" ref="cnsvg" :style="{height: this.height + 'px'}"></div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'ChartSvg',
    props: {
        name: String,
        path: String,
        height: Number,
        channel: String,
        listen: String,
        ratio: Number,
        defaultData: Object
    },
    data () {
        return {
            scadavis: null,
            tags: [],
            data: {},
            containerId: 'scada-svg'
        }
    },
    async mounted () {
        await this.init()
        await this.initListen();
    },
    created() {
        window.addEventListener("resize", this.resize);
        this.containerId = 'scada-svg-' + Math.random().toString(36).substring(2, 15);
    },
    destroyed() {
        window.removeEventListener("resize", this.resize);
    },
    methods: {
        async init() {
            let height = this.$refs.cnsvg.clientHeight;
            let width = this.$refs.cnsvg.clientWidth;

            this.scadavis = await scadavisInit({
                container: this.containerId,
                iframeparams: `frameborder="0" height="${height}" width="${width}"`,
                svgurl: this.path
            }).then((sv) => sv)

            this.scadavis.enableTools(false, false);
            this.scadavis.hideWatermark()
            this.tags = this.scadavis.getTagsList().split(",")
            this.tags.forEach(tag => {
                this.scadavis.setValue(tag, 0);
            });
            this.resize()
        },
        resize(){
            this.scadavis.zoomToOriginal();
            let svelem = this.$refs.cnsvg;
            let width = svelem.clientWidth;
            let height = svelem.clientHeight;
            let ratio = (width < height) ?  width / this.ratio : height / this.ratio;
    
            this.scadavis.zoomTo(ratio);
        },
        setValue(tag, val) {
            if(this.scadavis){
                this.scadavis.setValue(tag, val);
            }
        },
        async initListen() {
            let that = this
            window.Echo.channel(this.channel).listen(this.listen, (e) => {
                console.log(e)
                if(e.data.atg_id == that.defaultData.id) {
                    that.data = e.data
                    let status = e.data.status ? 'Online' : 'Offline';
                    that.setValue('status_text', status);
                    Object.keys(that.data).forEach(key => that.setValue(key, that.data[key]));
                }
            });
        },
        async load() {
            // 
        }
    }
}
</script>

<style lang="scss">
.scada {
    height: 100%;
    width: 100%;
}
iframe {
    border: 0;
    height: 100%;
    width: 100%;
}
</style>
