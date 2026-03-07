export default {
  id: 'roundedBars',
  beforeDraw(chart) {
    const {ctx, data, chartArea: {top, bottom, left, right, width, height}} = chart;
    
    chart.getDatasetMeta(0).data.forEach((bar, index) => {
      const vm = bar;
      const radius = 10;
      
      ctx.beginPath();
      ctx.fillStyle = vm.options.backgroundColor;
      ctx.strokeStyle = vm.options.borderColor;
      ctx.lineWidth = vm.options.borderWidth || 0;

      // Draw rounded rectangle
      const x = vm.x - vm.width / 2;
      const barHeight = vm.height;
      
      ctx.moveTo(x + radius, vm.y);
      ctx.arcTo(x + vm.width, vm.y, x + vm.width, vm.y + barHeight, radius);
      ctx.arcTo(x + vm.width, vm.y + barHeight, x, vm.y + barHeight, radius);
      ctx.arcTo(x, vm.y + barHeight, x, vm.y, radius);
      ctx.arcTo(x, vm.y, x + vm.width, vm.y, radius);
      
      ctx.closePath();
      ctx.fill();
      
      if (ctx.lineWidth > 0) {
        ctx.stroke();
      }
    });
  }
};