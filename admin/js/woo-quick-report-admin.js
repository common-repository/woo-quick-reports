jQuery(document).ready(function ($) {
    'use strict';

    $('.wqr-contents-block:nth-child(2)').addClass('wqr-contents-active');
    $('.wqr-awr-title-icon:nth-child(2)').addClass('wqr-awr-title-icon-active');
    $('.wqr-awr-title-icon').click(function () {
        $('.wqr-tab-active').removeClass('wqr-tab-active');
        var tab_link = $(this).attr('href');
        $(this).parent().addClass('wqr-tab-active').parent('.wqr-tabs-nav').next('.wqr-tabs-stage').find(tab_link).addClass('wqr-tab-active').siblings().removeClass('wqr-tab-active');
    });

    $('.wqr-awr-title-icon:first').click();
    $('.wqr-awr-title-icons .wqr-awr-title-icon').click(function () {
        var icon_select = $(this).attr('data-table');
        $(this).addClass('wqr-awr-title-icon-active').siblings().removeClass('wqr-awr-title-icon-active');
        $(this).parents('.wqr-awr-title').next('.wqr-contents-block-main').find('.wqr-contents-block#' + icon_select).addClass('wqr-contents-active').siblings('.wqr-contents-block').removeClass('wqr-contents-active');
    });
    var orders_charts = $.parseJSON($('input[name="orders_charts"]').val());
    var _browser_chart_data = orders_charts._orders_by_browser;
    var _coupons_chart_data = orders_charts._orders_by_coupons;
    var _device_chart_data = orders_charts._orders_by_device;
    var _line_items_chart_data = orders_charts._orders_by_line_items;
    var _payment_chart_data = orders_charts._orders_by_payment;
    var _shipping_chart_data = orders_charts._orders_by_shipping;
    var _status_chart_data = orders_charts._orders_by_statuses;

    am4core.ready(function () {
        am4core.useTheme(am4themes_animated);

        /**
         * Status Chart Data
         */
        if (0 < _status_chart_data.length) {
            /**
             * Status Chart
             * @type node {wqr-wc-status-chart}
             * @private
             */
            var _status_chart = am4core.create('wqr-wc-status-chart', am4charts.PieChart);
            _status_chart.data = _status_chart_data;
            _status_chart.innerRadius = am4core.percent(50);
            var _status_chart_series = _status_chart.series.push(new am4charts.PieSeries());
            _status_chart_series.dataFields.value = "value";
            _status_chart_series.dataFields.category = "category";
            _status_chart_series.slices.template.stroke = am4core.color("#FFF");
            _status_chart_series.slices.template.strokeWidth = 2;
            _status_chart_series.slices.template.strokeOpacity = 1;
            _status_chart_series.hiddenState.properties.opacity = 1;
            _status_chart_series.hiddenState.properties.endAngle = -90;
            _status_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Status bar chart.
             * @type node {wqr-wc-status-bar-chart}
             * @private
             */
            var _status_bar_chart = am4core.create('wqr-wc-status-bar-chart', am4charts.XYChart);
            _status_bar_chart.scrollbarX = new am4core.Scrollbar();
            _status_bar_chart.data = _status_chart_data;

            var categoryAxis = _status_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _status_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _status_bar_chart_series = _status_bar_chart.series.push(new am4charts.ColumnSeries());
            _status_bar_chart_series.sequencedInterpolation = true;
            _status_bar_chart_series.dataFields.valueY = "value";
            _status_bar_chart_series.dataFields.categoryX = "category";
            _status_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _status_bar_chart_series.columns.template.strokeWidth = 0;
            _status_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _status_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _status_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _status_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _status_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _status_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _status_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _status_bar_chart.cursor = new am4charts.XYCursor();
        }

        /**
         * Payment Chart Data
         */
        if (0 < _payment_chart_data.length) {
            /**
             * Payment Chart
             * @type node {wqr-wc-payment-method-chart}
             * @private
             */
            var _pm_chart = am4core.create('wqr-wc-payment-method-chart', am4charts.PieChart);
            _pm_chart.data = _payment_chart_data;
            _pm_chart.innerRadius = am4core.percent(50);
            var _pm_chart_series = _pm_chart.series.push(new am4charts.PieSeries());
            _pm_chart_series.dataFields.value = "value";
            _pm_chart_series.dataFields.category = "category";
            _pm_chart_series.slices.template.stroke = am4core.color("#FFF");
            _pm_chart_series.slices.template.strokeWidth = 2;
            _pm_chart_series.slices.template.strokeOpacity = 1;
            _pm_chart_series.hiddenState.properties.opacity = 1;
            _pm_chart_series.hiddenState.properties.endAngle = -90;
            _pm_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Payment method bar chart.
             * @type node {wqr-wc-payment-method-bar-chart}
             * @private
             */
            var _pm_bar_chart = am4core.create('wqr-wc-payment-method-bar-chart', am4charts.XYChart);
            _pm_bar_chart.scrollbarX = new am4core.Scrollbar();
            _pm_bar_chart.data = _payment_chart_data;

            var categoryAxis = _pm_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _pm_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _pm_bar_chart_series = _pm_bar_chart.series.push(new am4charts.ColumnSeries());
            _pm_bar_chart_series.sequencedInterpolation = true;
            _pm_bar_chart_series.dataFields.valueY = "value";
            _pm_bar_chart_series.dataFields.categoryX = "category";
            _pm_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _pm_bar_chart_series.columns.template.strokeWidth = 0;
            _pm_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _pm_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _pm_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _pm_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _pm_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _pm_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _pm_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _pm_bar_chart.cursor = new am4charts.XYCursor();
        }

        /**
         * Shipping Chart Data
         */
        if (0 < _shipping_chart_data.length) {
            /**
             * Shipping Chart
             * @type node {wqr-wc-payment-method-chart}
             * @private
             */
            var _shipping_chart = am4core.create('wqr-wc-shipping-method-chart', am4charts.PieChart);
            _shipping_chart.data = _shipping_chart_data;
            _shipping_chart.innerRadius = am4core.percent(50);
            var _shipping_chart_series = _shipping_chart.series.push(new am4charts.PieSeries());
            _shipping_chart_series.dataFields.value = "value";
            _shipping_chart_series.dataFields.category = "category";
            _shipping_chart_series.slices.template.stroke = am4core.color("#FFF");
            _shipping_chart_series.slices.template.strokeWidth = 2;
            _shipping_chart_series.slices.template.strokeOpacity = 1;
            _shipping_chart_series.hiddenState.properties.opacity = 1;
            _shipping_chart_series.hiddenState.properties.endAngle = -90;
            _shipping_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Shipping methods bar chart.
             * @type node {wqr-wc-shipping-method-bar-chart}
             * @private
             */
            var _shipping_bar_chart = am4core.create('wqr-wc-shipping-method-bar-chart', am4charts.XYChart);
            _shipping_bar_chart.scrollbarX = new am4core.Scrollbar();
            _shipping_bar_chart.data = _shipping_chart_data;

            var categoryAxis = _shipping_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _shipping_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _shipping_bar_chart_series = _shipping_bar_chart.series.push(new am4charts.ColumnSeries());
            _shipping_bar_chart_series.sequencedInterpolation = true;
            _shipping_bar_chart_series.dataFields.valueY = "value";
            _shipping_bar_chart_series.dataFields.categoryX = "category";
            _shipping_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _shipping_bar_chart_series.columns.template.strokeWidth = 0;
            _shipping_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _shipping_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _shipping_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _shipping_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _shipping_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _shipping_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _shipping_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _shipping_bar_chart.cursor = new am4charts.XYCursor();
        }

        /**
         * Browser Chart Data
         */
        if (0 < _browser_chart_data.length) {
            /**
             * Browser Chart
             * @type node {wqr-wc-browser-chart}
             * @private
             */
            var _browser_chart = am4core.create('wqr-wc-browser-chart', am4charts.PieChart);
            _browser_chart.data = _browser_chart_data;
            _browser_chart.innerRadius = am4core.percent(50);
            var _browser_chart_series = _browser_chart.series.push(new am4charts.PieSeries());
            _browser_chart_series.dataFields.value = "value";
            _browser_chart_series.dataFields.category = "category";
            _browser_chart_series.slices.template.stroke = am4core.color("#FFF");
            _browser_chart_series.slices.template.strokeWidth = 2;
            _browser_chart_series.slices.template.strokeOpacity = 1;
            _browser_chart_series.hiddenState.properties.opacity = 1;
            _browser_chart_series.hiddenState.properties.endAngle = -90;
            _browser_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Browser bar chart.
             * @type node {wqr-wc-browser-bar-chart}
             * @private
             */
            var _browser_bar_chart = am4core.create('wqr-wc-browser-bar-chart', am4charts.XYChart);
            _browser_bar_chart.scrollbarX = new am4core.Scrollbar();
            _browser_bar_chart.data = _browser_chart_data;

            var categoryAxis = _browser_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _browser_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _browser_bar_chart_series = _browser_bar_chart.series.push(new am4charts.ColumnSeries());
            _browser_bar_chart_series.sequencedInterpolation = true;
            _browser_bar_chart_series.dataFields.valueY = "value";
            _browser_bar_chart_series.dataFields.categoryX = "category";
            _browser_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _browser_bar_chart_series.columns.template.strokeWidth = 0;
            _browser_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _browser_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _browser_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _browser_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _browser_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _browser_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _browser_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _browser_bar_chart.cursor = new am4charts.XYCursor();
        }

        /**
         * Device Chart Data
         */
        if (0 < _device_chart_data.length) {
            /**
             * Device Chart
             * @type node {wqr-wc-device-method-chart}
             * @private
             */
            var _device_chart = am4core.create('wqr-wc-device-chart', am4charts.PieChart);
            _device_chart.data = _device_chart_data;
            _device_chart.innerRadius = am4core.percent(50);
            var _device_chart_series = _device_chart.series.push(new am4charts.PieSeries());
            _device_chart_series.dataFields.value = "value";
            _device_chart_series.dataFields.category = "category";
            _device_chart_series.slices.template.stroke = am4core.color("#FFF");
            _device_chart_series.slices.template.strokeWidth = 2;
            _device_chart_series.slices.template.strokeOpacity = 1;
            _device_chart_series.hiddenState.properties.opacity = 1;
            _device_chart_series.hiddenState.properties.endAngle = -90;
            _device_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Device bar chart.
             * @type node {wqr-wc-device-bar-chart}
             * @private
             */
            var _device_bar_chart = am4core.create('wqr-wc-device-bar-chart', am4charts.XYChart);
            _device_bar_chart.scrollbarX = new am4core.Scrollbar();
            _device_bar_chart.data = _device_chart_data;

            var categoryAxis = _device_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _device_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _device_bar_chart_series = _device_bar_chart.series.push(new am4charts.ColumnSeries());
            _device_bar_chart_series.sequencedInterpolation = true;
            _device_bar_chart_series.dataFields.valueY = "value";
            _device_bar_chart_series.dataFields.categoryX = "category";
            _device_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _device_bar_chart_series.columns.template.strokeWidth = 0;
            _device_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _device_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _device_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _device_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _device_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _device_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _device_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _device_bar_chart.cursor = new am4charts.XYCursor();
        }

        /**
         * Line Items Chart Data
         */
        if (0 < _line_items_chart_data.length) {
            /**
             * Line items chart.
             * @type node {wqr-wc-line-items-chart}
             * @private
             */
            var _line_items_chart = am4core.create('wqr-wc-line-items-chart', am4charts.PieChart);
            _line_items_chart.data = _line_items_chart_data;
            _line_items_chart.innerRadius = am4core.percent(50);
            var _line_items_chart_series = _line_items_chart.series.push(new am4charts.PieSeries());
            _line_items_chart_series.dataFields.value = "value";
            _line_items_chart_series.dataFields.category = "category";
            _line_items_chart_series.slices.template.stroke = am4core.color("#FFF");
            _line_items_chart_series.slices.template.strokeWidth = 2;
            _line_items_chart_series.slices.template.strokeOpacity = 1;
            _line_items_chart_series.hiddenState.properties.opacity = 1;
            _line_items_chart_series.hiddenState.properties.endAngle = -90;
            _line_items_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Line items bar chart.
             * @type node {wqr-wc-line-items-bar-chart}
             * @private
             */
            var _line_items_bar_chart = am4core.create('wqr-wc-line-items-bar-chart', am4charts.XYChart);
            _line_items_bar_chart.scrollbarX = new am4core.Scrollbar();
            _line_items_bar_chart.data = _line_items_chart_data;

            var categoryAxis = _line_items_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _line_items_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _line_items_bar_chart_series = _line_items_bar_chart.series.push(new am4charts.ColumnSeries());
            _line_items_bar_chart_series.sequencedInterpolation = true;
            _line_items_bar_chart_series.dataFields.valueY = "value";
            _line_items_bar_chart_series.dataFields.categoryX = "category";
            _line_items_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _line_items_bar_chart_series.columns.template.strokeWidth = 0;
            _line_items_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _line_items_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _line_items_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _line_items_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _line_items_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _line_items_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _line_items_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _line_items_bar_chart.cursor = new am4charts.XYCursor();
        }

        /**
         * Coupons Chart Data
         */
        if (0 < _coupons_chart_data.length) {
            /**
             * Coupons chart
             * @type node {wqr-wc-coupons-chart}
             * @private
             */
            var _coupons_chart = am4core.create('wqr-wc-coupons-chart', am4charts.PieChart);
            _coupons_chart.data = _coupons_chart_data;
            _coupons_chart.innerRadius = am4core.percent(50);
            var _coupons_chart_series = _coupons_chart.series.push(new am4charts.PieSeries());
            _coupons_chart_series.dataFields.value = "value";
            _coupons_chart_series.dataFields.category = "category";
            _coupons_chart_series.slices.template.stroke = am4core.color("#FFF");
            _coupons_chart_series.slices.template.strokeWidth = 2;
            _coupons_chart_series.slices.template.strokeOpacity = 1;
            _coupons_chart_series.hiddenState.properties.opacity = 1;
            _coupons_chart_series.hiddenState.properties.endAngle = -90;
            _coupons_chart_series.hiddenState.properties.startAngle = -90;

            /**
             * Coupons bar chart.
             * @type node {wqr-wc-coupons-bar-chart}
             * @private
             */
            var _coupons_bar_chart = am4core.create('wqr-wc-coupons-bar-chart', am4charts.XYChart);
            _coupons_bar_chart.scrollbarX = new am4core.Scrollbar();
            _coupons_bar_chart.data = _coupons_chart_data;

            var categoryAxis = _coupons_bar_chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "category";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = _coupons_bar_chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            var _coupons_bar_chart_series = _coupons_bar_chart.series.push(new am4charts.ColumnSeries());
            _coupons_bar_chart_series.sequencedInterpolation = true;
            _coupons_bar_chart_series.dataFields.valueY = "value";
            _coupons_bar_chart_series.dataFields.categoryX = "category";
            _coupons_bar_chart_series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
            _coupons_bar_chart_series.columns.template.strokeWidth = 0;
            _coupons_bar_chart_series.tooltip.pointerOrientation = "vertical";
            _coupons_bar_chart_series.columns.template.column.cornerRadiusTopLeft = 10;
            _coupons_bar_chart_series.columns.template.column.cornerRadiusTopRight = 10;
            _coupons_bar_chart_series.columns.template.column.fillOpacity = 0.8;

            var hoverState = _coupons_bar_chart_series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;
            _coupons_bar_chart_series.columns.template.adapter.add("fill", function (fill, target) {
                return _coupons_bar_chart.colors.getIndex(target.dataItem.index);
            });
            _coupons_bar_chart.cursor = new am4charts.XYCursor();
        }
    });
});