<script setup>
import Title from '../title.vue';
import ExportReadingBarGraphics from "./ExportReadingBarGraphics.vue";
import ExportWritingBarGraphic from "./ExportWritingBarGraphic.vue";
import ExportReadingPieGraphic from "./ExportReadingPieGraphic.vue";
import ExportWritingPieChart from "./ExportWritingPieChart.vue";
import {onMounted} from "vue";
import html2pdf from "html2pdf.js";

onMounted(() => {
    const element = document.getElementById('content')
    html2pdf()
        .from(element)
        .set({
            margin: [5, 5, 5, 5],
            filename: 'exported-file.pdf',
            html2canvas: {
                scale: 3,
                useCORS: true,
                scrollX: 0,
                scrollY: 0,
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        })
        .save()
})

</script>

<template>
    <div id="content">
        <Title class="average-modal" id='bar-chart-title' title="Análise geral dividida por sondagem" />
        <ExportReadingBarGraphics/>
        <ExportWritingBarGraphic/>
        <Title class="average-modal" id='pie-chart-title' title="Análise geral média" />
        <ExportReadingPieGraphic/>
        <ExportWritingPieChart/>
    </div>
</template>

<style scoped>
.average-modal{
    display: flex;
    justify-content: center;
    width: 95% !important;
    margin: 15px !important;
}
</style>
