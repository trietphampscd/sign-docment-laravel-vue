<template>
  <canvas
    id="canvas"
    v-on:mousedown="handleMouseDown"
    v-on:mouseup="handleMouseUp"
    v-on:mousemove="handleMouseMove"
    width="535"
    height="280"
  ></canvas>
</template>
<script>
export default {
  data: function() {
    return {
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
      var c = document.getElementById("canvas");
      var rect = c.getBoundingClientRect();

      return {
        x: this.mouse.current.x - rect.left,
        y: this.mouse.current.y - rect.top
      };
    }
  },
  methods: {
    draw: function(event) {
      // requestAnimationFrame(this.draw);
      if (this.mouse.down) {
        var c = document.getElementById("canvas");

        var ctx = c.getContext("2d");

        ctx.clearRect(0, 0, 800, 800);

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

      var c = document.getElementById("canvas");
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
    }
  },
  ready: function() {
    var c = document.getElementById("canvas");
    var ctx = c.getContext("2d");
    ctx.translate(0.5, 0.5);
    ctx.imageSmoothingEnabled = false;
    // this.draw();
  }
}
</script>