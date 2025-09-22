<template>
    <div id="scada-svg" class="scada" ref="cnsvg" :style="{height: this.height + 'px'}"></div>
</template>

<script>
export default {
    name: 'ChartHomeSvg',
    props: {
        path: String,
        height: Number,
        channel: String,
        listen: String,
        ratio: Number,
    },
    data () {
        return {
            scadavis: null,
            tags: [],
            data: {},
        }
    },
    async mounted () {
        await this.init()
        this.initListen();
    },
    created() {
        window.addEventListener("resize", this.resize);
    },
    destroyed() {
        window.removeEventListener("resize", this.resize);
    },
    methods: {
        async init() {
            let height = this.$refs.cnsvg.clientHeight;
            let width = this.$refs.cnsvg.clientWidth;

            this.scadavis = await scadavisInit({
                container: "scada-svg",
                iframeparams: `frameborder="0" height="${height}" width="${width}"`,
                svgurl: this.path
            }).then((sv) => sv)

            this.scadavis.enableTools(false, false);
            this.scadavis.hideWatermark()
            this.tags = this.scadavis.getTagsList().split(",")
            this.tags.forEach(tag => {
                if(tag  == 'storage1_status_text' || tag  == 'storage2_status_text') {
                    this.scadavis.setValue(tag, 'Offline');
                }else{
                    this.scadavis.setValue(tag, parseFloat(0));
                }
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
        initListen() {
            let that = this
            window.Echo.channel(this.channel).listen(this.listen, (e) => {
                console.log(e)
                let data = e.data
                let status = data.status ? 'Online': 'Offline'
                if(data.atg_id == 1) {
                    that.setValue(`storage1_status_text`, status)
                    Object.keys(data).forEach(key => that.setValue(`storage1_${key}`, data[key]));
                }else{
                    that.setValue(`storage2_status_text`, status)
                    Object.keys(data).forEach(key => that.setValue(`storage2_${key}`, data[key]));
                }
                // other share data
                Object.keys(data).forEach(key => that.setValue(key, data[key]));
            });
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
