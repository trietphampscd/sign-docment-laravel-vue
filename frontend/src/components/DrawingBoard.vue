<template>
  <div class="canvas-border">
    <canvas
      :ref="canvas_id"
      v-on:mousedown="handleMouseDown"
      v-on:mouseup="handleMouseUp"
      v-on:mousemove="handleMouseMove"
      v-bind="stageSize"
    ></canvas>
  </div>
</template>

<script>
export default {
  props: {
    paramData: Object,
    drawSize: Object
  },
  data: function() {
    return {
      canvas_id: '',
      stageSize: {
        width: this.drawSize.width,
        height: this.drawSize.height
      },
      mouse: {
        current: {
          x: 0,
          y: 0
        },
        previous: {
          x: 0,
          y: 0
        },
        down: false
      }
    };
  },
  computed: {
    currentMouse: function() {
      var c = this.$refs[this.canvas_id];
      var rect = c.getBoundingClientRect();

      return {
        x: this.mouse.current.x - rect.left,
        y: this.mouse.current.y - rect.top
      };
    }
  },
  mounted() {
    this.canvas_id = 'canvas-' + this.paramData.name
  },
  methods: {
    draw: function(event) {
      // requestAnimationFrame(this.draw);
      if (this.mouse.down) {
        var c = this.$refs[this.canvas_id];

        var ctx = c.getContext("2d");

        ctx.clearRect(0, 0, this.drawSize.width, this.drawSize.height);

        ctx.lineTo(this.currentMouse.x, this.currentMouse.y);
        ctx.strokeStyle = "black";
        ctx.lineWidth = 2;
        ctx.stroke();
      }
    },
    handleMouseDown: function(event) {
      this.mouse.down = true;
      this.mouse.current = {
        x: event.pageX,
        y: event.pageY
      };
      
      var c = this.$refs[this.canvas_id];
      var ctx = c.getContext("2d");

      ctx.moveTo(this.currentMouse.x, this.currentMouse.y);
    },
    handleMouseUp: function() {
      this.mouse.down = false;
    },
    handleMouseMove: function(event) {
      this.mouse.current = {
        x: event.pageX,
        y: event.pageY
      };

      this.draw(event);
    },
    getDataURL: function () {
      let c = this.$refs[this.canvas_id];

      return c.toDataURL('image/png')
    }
  },
  ready: function() {
    var c = this.$refs[this.canvas_id];
    let ctx = c.getContext('2d', {antialias: false});
    ctx.translate(0.5, 0.5);
    ctx.mozImageSmoothingEnabled = false;
    ctx.webkitImageSmoothingEnabled = false;
    ctx.msImageSmoothingEnabled = false;
    ctx.imageSmoothingEnabled = false;
    // this.draw();
  }
}
</script>

<style lang="scss" scoped>
.canvas-border{
  border: solid 2px #ebebeb;
}
</style>