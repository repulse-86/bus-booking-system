<script setup>
import { onMounted } from 'vue';
import ApexCharts from 'apexcharts'

const props = defineProps({
	cumulativePerMonthSales: Array,
	cumulativePerMonthUserCount: Array,
	cumulativePerMonthBookingCount: Array,
});

onMounted(() => {
	if (document.getElementById("data-series-chart") && typeof ApexCharts !== 'undefined') {
		const chart = new ApexCharts(document.getElementById("data-series-chart"), options);
		chart.render();
	}
})

const totalSales = props.cumulativePerMonthSales.reduce((total, item) => total + item.total_sales, 0);

const options = {
series: [
  {
    name: "Sales",
    data:  props.cumulativePerMonthSales.map(item => item.total_sales),
    color: "#1A56DB",
  },
  {
    name: "Customers",
    data: props.cumulativePerMonthUserCount.map(item => item.total_users),
    color: "#7E3BF2",
  },
  {
    name: "Bookings",
    data: props.cumulativePerMonthBookingCount.map(item => item.total_bookings),
    color: "#7E3BF2",
  },
],
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
legend: {
  show: false
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
  enabled: false,
},
stroke: {
  width: 6,
},
grid: {
  show: false,
  strokeDashArray: 4,
  padding: {
    left: 2,
    right: 2,
    top: 0
  },
},
xaxis: {
  categories: props.cumulativePerMonthUserCount.map(item => item.month_name),
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
  labels: {
    formatter: function (value) {
      return value;
    }
  }
},
}
</script>

<template>
	<div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
		<div class="flex justify-between">
			<div>
				<h5 class="leading-none text-xl font-bold text-gray-800 dark:text-white pb-2">P {{ totalSales }}</h5>
				<p class="text-base font-normal text-gray-500 dark:text-gray-400">Cumulative sales, users, & bookings per month</p>
			</div>
		</div>
		<div id="data-series-chart"></div>
	</div>
</template>