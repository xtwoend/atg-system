<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ title }}</h3>
            <div class="card-actions btn-actions">
                <button class="btn-action" @click="loadFromServer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
                        <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
                    </svg>
                </button>
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
                    <a :href="downloadUrl" class="dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-xls">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
                            <path d="M4 15l4 6" />
                            <path d="M4 21l4 -6" />
                            <path
                                d="M17 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" />
                            <path d="M11 15v6h3" />
                        </svg>
                        Export to Excel
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <EasyDataTable 
                show-index 
                show-index-symbol="No." 
                alternating
                fixed-header 
                buttons-pagination
                v-model:server-options="options"
                :server-items-length="serverItemsLength" 
                :headers="headers" 
                :items="items" 
                :loading="isLoading"
                header-text-direction="center" 
                body-text-direction="right" 
                :rows-items="[20, 50, 100]">
                <template v-for="(_, slot) in $slots" v-slot:[slot]="scope">
                    <slot :name="slot" v-bind="scope || {}" />
                </template>
            </EasyDataTable>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    headers: { type: Object, required: true },
    url: String,
    downloadUrl: String,
    request: Object,
    title: {
        default: "Data Logger",
        type: String
    }
})

const params = ref({})
const items = ref([])
const isLoading = ref(false);
const serverItemsLength = ref(0);
const options = ref({
    page: 1,
    rowsPerPage: 20,
    sortBy: 'terminal_time',
    sortType: 'desc',
});

params.value = props.request

const loadFromServer = async () => {
    isLoading.value = true;
    let res = await axios.get(props.url, { params: params.value }).then(res => res.data);
    let rows = [];
    res.data.forEach(row => {
        Object.keys(row).forEach(function (key, index) {
            if (row[key] > 999) {
                row[key] = !isNaN(row[key]) ? Number(row[key]).toLocaleString() : row[key];
            } else {
                row[key] = row[key];
            }
        })
        rows.push(row)
    });
    
    items.value = rows
    serverItemsLength.value = res.total;
    isLoading.value = false;
}

loadFromServer();

watch(options, (value) => {
    Object.assign(params.value, value)
    loadFromServer();
}, { deep: true });

</script>

<style lang="scss">
.vue3-easy-data-table {
    border: none !important;
}

.vue3-easy-data-table__header th {
    // background-color: var(--tblr-bg-surface-tertiary) !important;
    font-size: 0.625rem;
    // font-weight: var(--tblr-font-weight-bold);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    line-height: 1rem;
    // color: var(--tblr-secondary) !important;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    white-space: nowrap;
    // border-bottom: 1px solid var(--tblr-border-color) !important;
}

// .vue3-easy-data-table__footer {
//     background-color: var(--tblr-bg-surface-tertiary) !important;
// }

// .vue3-easy-data-table__main {
//     background-color: var(--tblr-bg-surface) !important;
// }

// .vue3-easy-data-table__body td {
//     background-color: var(--tblr-table-bg) !important;
//     color: initial !important;
// }
</style>