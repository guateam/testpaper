//初始化柱状图
var line = echarts.init(document.getElementById('line'));
var lineoption = {
    title: {
        text: '试卷录入概况'
    },
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow',
            label: {
                show: true
            }
        }
    },
    legend: {
        data: ['录入量', '审核量'],
    },
    xAxis: [{
        type: 'category',
        data: []
    }],
    yAxis: [{
        type: 'value',
        name: '试卷数量',
    }],
    dataZoom: [{
            show: true,
            start: 0,
            end: 100
        },
        {
            type: 'inside',
            start: 0,
            end: 100
        }
    ],
    series: [{
        name: '录入量',
        type: 'bar',
        data: []
    }, {
        name: '审核量',
        type: 'bar',
        data: []
    }, ]
};
line.setOption(lineoption)
    //初始化饼状图
var panoption = {
    title: {
        text: '当前正在运行的试卷',
        x: 'center'
    },
    tooltip: {
        label: {
            show: true
        }
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['正在录入', '等待分配', '等待审核', '被打回']
    },
    series: [{
        name: '试卷数量',
        type: 'pie',
        radius: '55%',
        center: ['50%', '60%'],
        data: [],
        itemStyle: {
            emphasis: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
    }]
}
var pan = echarts.init(document.getElementById('pan'));
pan.setOption(panoption);
//获取柱状图数据
$.post('/testpaper/public/index.php/admin/workinglist/getlinedataforuser/id/' + $.cookie('id')).done((data) => {
    if (data.status == 1) {
        line.setOption(data.data)
    }
});
//获取饼状图数据
$.post('/testpaper/public/index.php/admin/workinglist/getpandataforuser/id/' + $.cookie('id')).done((data) => {
    if (data.status == 1) {
        pan.setOption({ series: [{ name: '试卷数量', data: data.data }] })
    }
})