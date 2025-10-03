import ApexCharts from 'apexcharts';

const chartSeven = () => {
	const chartElement = document.getElementById('chartSeven');

	if (!chartElement) return;

	// Get data from global variable set by PHP
	const data = window.vmsDeviceChartData || {
		desktop: 45,
		mobile: 65,
		tablet: 25,
	};

	const options = {
		series: [data.desktop, data.mobile, data.tablet],
		chart: {
			type: 'donut',
			height: 326,
			fontFamily: 'Outfit, sans-serif',
			toolbar: {
				show: false,
			},
		},
		labels: ['Desktop', 'Mobile', 'Tablet'],
		colors: ['#3641f5', '#7592ff', '#dde9ff'],
		dataLabels: {
			enabled: false,
		},
		plotOptions: {
			pie: {
				donut: {
					size: '65%',
					labels: {
						show: false,
					},
				},
			},
		},
		legend: {
			position: 'bottom',
			horizontalAlign: 'center',
			fontSize: '14px',
			fontWeight: 400,
			fontFamily: 'Outfit',
			markers: {
				width: 10,
				height: 10,
				radius: 10,
			},
			itemMargin: {
				horizontal: 10,
				vertical: 0,
			},
			labels: {
				colors: '#373d3f',
			},
		},
		stroke: {
			width: 0,
		},
		responsive: [
			{
				breakpoint: 640,
				options: {
					chart: {
						height: 280,
					},
					legend: {
						fontSize: '12px',
					},
				},
			},
		],
	};

	const chart = new ApexCharts(chartElement, options);
	chart.render();
};

export default chartSeven;
