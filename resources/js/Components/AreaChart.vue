<script setup>
import ApexCharts from 'apexcharts';
import { formatNumber } from '@/helpers';
import { onMounted } from 'vue'

const props = defineProps({
	chartData: Object,
	label: String,
});

onMounted(() => {
    if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("area-chart"), options);
        chart.render();
    }
});

const totalCount = formatNumber(props.chartData.data.reduce((sum, count) => sum + count, 0));

const options = {
    chart: {
        height: "100%",
        maxWidth: "100%",
        type: "area",
        fontFamily: "Inter, sans-serif",
        dropShadow: {
            enabled: false,
        },
        toolbar: {
            show: false,
        },
    },
    tooltip: {
        enabled: true,
        x: {
            show: false,
        },
    },
    fill: {
        type: "gradient",
        gradient: {
            opacityFrom: 0.55,
            opacityTo: 0,
            shade: "#1C64F2",
            gradientToColors: ["#1C64F2"],
        },
    },
    dataLabels: {
        enabled: true,
    },
    stroke: {
        width: 4,
    },
    grid: {
        show: true,
        strokeDashArray: 4,
        padding: {
            left: 2,
            right: 2,
            top: 0,
        },
    },
    series: [
        {
            name: props.label,
            data: props.chartData.data,
            color: "#1A56DB",
        },
    ],
    xaxis: {
        categories: props.chartData.categories,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        show: false,
    },
};
</script>

<template>
	<div class="">
	    <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
	        <div class="flex justify-between">
	            <div>
	                <h5 class="leading-none text-xl font-bold text-gray-800 dark:text-white pb-2">{{ totalCount }}</h5>
	                <p class="text-base font-normal text-gray-500 dark:text-gray-400">{{ label }} this week</p>
	            </div>
	        </div>
	        <div id="area-chart"></div>
	    </div>
	</div>
</template>