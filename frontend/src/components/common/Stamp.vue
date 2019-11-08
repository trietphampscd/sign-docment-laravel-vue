<template>
  <div>
    <!-- Personnel Type -->
    <div 
      v-if="paramStampInner.stamp_type=='Personnel'" 
      :class="`listing-stamp sign-result ${classChecked}`" 
      @click="btnClickHandler(keyItem)"
    >
      <div 
        :class="`default-stamp ${configStampInner.language}-stamp`" 
        :style="`font-family: ${paramStampInner.font_face}; font-size: ${ paramStampInner.font_size }px; ${configStampInner.language == 'gb' ? 'font-weight: bold;' : ''}`"
      >
        <span v-html="configStampInner.display_name"></span>
        <span v-if="paramStampInner.etc.position">{{ paramStampInner.etc.position }}</span>
        <span v-if="paramStampInner.etc.company">{{ paramStampInner.etc.company }}</span>
      </div>
    </div>
    
    <!-- Upload Type -->
    <div v-else-if="paramStampInner.stamp_type=='Upload'"  class="listing-stamp sign-result" style="overflow: hidden;">
      <img :src="paramStampInner.uploaded_url" alt="Uploaded Image" />
    </div>

    <!-- Corporate Type -->
    <div 
      v-else 
      :class="`listing-stamp sign-result ${classChecked}`" 
      @click="btnClickHandler(keyItem)"
    >
      <div 
        v-if="configStampInner.language=='gb'" 
        :class="`default-stamp ${configStampInner.language}-stamp`" 
        :style="`font-family: ${paramStampInner.font_face}; font-size: ${paramStampInner.font_size}px`"
      >       
        <p 
          :style="{
            'font-weight: bold;': configStampInner.language == 'gb'
          }"
        >
          {{ paramStampInner.stamp_text }}
        </p>        
      </div>

      <svg v-else version="1.0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460 450" preserveAspectRatio="xMidYMid meet">
        <defs>
          <path id="circleTextPath"
                d="M210,340
                  a130,130 0 0,1 0,-270
                  a130,130 0 0,1 0,270Z"
                transform="rotate(160,200,200)"/>
        </defs>
  
        <g transform="translate(0, 690) scale(0.1,-0.1)" fill="red" stroke="none">
          <!-- Start Default -->
            <!-- Outer Circle -->
            <path d="M2000 6700 c-204 -29 -460 -109 -635 -197 -382 -192 -727 -535 -920
              -917 -91 -179 -170 -436 -199 -648 -21 -147 -21 -417 0 -564 29 -212 108 -469
              199 -648 192 -379 536 -723 915 -915 179 -91 436 -170 648 -199 147 -21 417
              -21 564 0 212 29 469 108 648 199 379 192 723 536 915 915 91 179 170 436 199
              648 21 147 21 417 0 564 -29 212 -108 469 -199 648 -192 379 -536 723 -915
              915 -179 91 -436 170 -648 199 -139 20 -438 20 -572 0z m480 -94 c362 -38 700
              -171 993 -392 105 -79 296 -270 375 -375 534 -709 534 -1657 0 -2366 -79 -105
              -270 -296 -375 -375 -709 -534 -1657 -534 -2366 0 -105 79 -296 270 -375 375
              -534 709 -534 1657 0 2366 79 105 270 296 375 375 398 300 894 442 1373 392z"/>
            <!-- Inner & Text Inside -->  
            <path d="M2175 5800 c-452 -50 -819 -335 -978 -756 -91 -240 -91 -568 0 -808
              121 -321 370 -570 691 -691 240 -91 568 -91 808 0 321 121 570 370 691 691 91
              240 91 568 0 808 -121 321 -370 570 -691 691 -142 53 -371 82 -521 65z m271 
              -95 c55 -8 143 -30 197 -48 105 -37 254 -116 241 -129 -15 -15 -226 -30 -322
              -23 -164 12 -159 13 -177 -16 -10 -16 -13 -36 -9 -52 8 -33 80 -90 116 -91 18
              -1 25 -5 19 -10 -5 -6 -9 -25 -9 -44 0 -25 5 -35 20 -39 19 -5 24 -18 43 -102
              6 -24 5 -24 -56 -18 -34 4 -72 9 -84 13 -28 8 -63 -27 -63 -64 0 -44 91 -119
              130 -109 17 4 20 0 20 -34 0 -38 -1 -39 -33 -39 -19 0 -56 7 -83 15 -69 21
              -100 19 -118 -7 -9 -12 -16 -31 -16 -41 0 -25 101 -124 135 -133 17 -4 59 2
              113 17 79 22 107 24 292 24 177 0 211 -3 252 -19 56 -22 128 -18 158 9 44 40
              32 133 -20 160 -37 19 -74 19 -136 0 -27 -8 -52 -15 -56 -15 -5 0 -8 23 -8 51
              l0 50 55 -6 55 -7 16 37 c21 52 16 65 -41 103 -49 33 -81 38 -127 18 -14 -7
              -13 2 7 61 44 122 33 142 -86 164 -80 15 -95 10 -104 -32 -6 -26 -3 -36 13
              -50 22 -18 26 -35 24 -116 l-2 -53 -48 0 -48 0 4 89 c5 104 6 103 -104 122
              l-69 12 33 14 c46 19 225 36 302 29 36 -4 99 -11 140 -18 l76 -11 49 -63 c117
              -149 191 -322 221 -517 44 -283 -46 -602 -232 -825 l-44 -54 0 122 0 121 22
              -15 c12 -9 42 -16 65 -16 60 0 83 25 83 89 0 42 -4 53 -26 70 -32 25 -74 27
              -115 5 l-31 -15 4 149 c3 136 1 152 -16 171 -24 26 -111 59 -180 67 -48 6 -55
              4 -65 -14 -22 -41 -13 -70 28 -96 23 -15 43 -38 50 -57 7 -21 11 -149 11 -381
              l0 -348 -57 -37 c-188 -120 -448 -180 -668 -154 -120 14 -244 47 -342 91 -69
              32 -204 119 -263 170 -34 30 -34 31 -7 21 16 -6 58 -11 95 -11 121 0 212 64
              248 173 24 72 16 243 -15 312 -26 60 -74 112 -118 131 -20 9 -33 22 -33 32 0
              10 -7 28 -16 40 -12 18 -25 22 -67 22 -68 -1 -122 -26 -175 -84 -76 -82 -109
              -196 -99 -344 l5 -86 -24 39 c-98 158 -164 385 -164 565 0 160 60 384 141 525
              42 74 44 76 50 49 4 -16 7 -70 8 -119 1 -73 -3 -98 -19 -127 -23 -44 -18 -67
              28 -116 35 -37 49 -39 157 -13 86 21 157 50 218 88 l37 23 0 -37 c0 -56 19
              -112 45 -139 20 -20 35 -24 80 -24 31 0 71 7 90 14 l35 15 0 -29 c0 -16 4 -40
              10 -54 8 -23 15 -26 54 -26 33 0 52 6 73 24 l28 24 5 399 c6 443 7 439 -58
              472 -47 24 -153 47 -176 38 -20 -8 -39 -53 -31 -77 3 -10 19 -23 36 -30 51
              -21 59 -43 59 -157 l0 -103 -40 0 -40 0 0 114 0 114 -30 25 c-30 26 -95 51
              -164 63 l-39 7 46 24 c75 37 220 83 315 97 117 19 198 19 316 1z m-704 -154
              c-19 -37 -11 -60 30 -86 21 -13 44 -33 50 -44 6 -12 10 -94 10 -200 l0 -180
              -67 -21 c-37 -11 -93 -23 -124 -27 l-56 -6 -6 69 c-11 114 -8 232 5 240 7 5
              46 9 87 9 82 2 101 14 101 66 0 43 -90 96 -149 87 -17 -2 -28 -1 -25 4 6 9
              144 108 151 108 2 0 -1 -9 -7 -19z m340 -560 c0 -117 -2 -140 -16 -145 -24 -9
              -31 -8 -48 10 -13 12 -16 41 -16 145 l0 129 40 0 40 0 0 -139z m736 -13 c-3
              -29 -6 -61 -6 -70 0 -15 -9 -18 -59 -18 l-58 0 -7 46 c-11 85 -12 83 52 87 31
              2 63 4 71 5 11 2 12 -9 7 -50z m-1102 -603 c34 -37 51 -108 50 -215 0 -74 -5
              -105 -21 -141 -38 -85 -114 -87 -157 -4 -17 33 -21 59 -21 145 0 113 13 172
              46 214 27 34 72 35 103 1z m252 283 c-22 -42 -14 -70 29 -97 23 -14 45 -37 50 
              -51 6 -15 10 -184 10 -398 0 -276 3 -375 12 -384 7 -7 31 -12 55 -12 33 0 50 
              6 72 28 l30 27 3 407 c3 385 2 407 -16 427 -24 26 -111 59 -180 67 -48 6 -55
              4 -65 -14z m452 -130 c-19 -35 -14 -80 10 -87 32 -10 40 -47 29 -128 -21 -146
              -79 -285 -164 -389 -25 -31 -46 -62 -46 -68 0 -36 75 -9 168 63 53 40 136 137
              165 192 l16 30 20 -46 c11 -25 27 -86 35 -134 16 -102 34 -125 95 -125 75 0
              104 55 82 156 -15 66 -80 158 -146 205 -36 26 -45 38 -40 54 3 11 11 60 18
              109 l11 88 -26 23 c-31 26 -119 62 -177 71 -33 5 -41 3 -50 -14z m-180 1850 
              c-70 -35 -102 -88 -102 -168 0 -73 27 -127 82 -163 35 -24 52 -28 108 -28 56 
              0 73 4 108 28 82 54 109 175 57 263 -29 49 -107 94 -165 94 -20 0 -60 -12 -88 -26z"/>
          <!-- End Default -->
        </g>
  
  
        <svg x="40" y="25">
          <text id="circleText" font-size="65px" fill="#ff0000" v-bind:style="`font-family: ${paramStampInner.font_face};`">
            <textPath 
              href="#circleTextPath" 
              aria-label="Company's Name" 
              textLength="942" 
              v-html="configStampInner.display_name"></textPath>
          </text>
        </svg>
      </svg>
    </div>

  </div>
</template>

<script>
import { isNullOrUndefined } from 'util'
export default {
  name: "Stamp",
  props: {
    keyItem: Number,
    paramStamp: Object,
    configStamp: Object,
    classChecked: String,
    btnClickHandler: Function
  },
  data() {
    return {
      paramStampInner: {
        stamp_id: '',
        stamp_type: '',
        stamp_title: '',
        stamp_text: '',
        font_face: '',
        font_size: '',
        etc: {
          position: '',
          company: ''
        },
        uploaded_url: ''
      },
      configStampInner: {
        language: '',
        str_length: '',
        display_name: ''
      }
    }
  },
  computed: {},
  mounted() {
    this.paramStampInner = this.paramStamp
    this.configStampInner = this.configStamp

    /** Display Config */
    if (this.paramStampInner.stamp_type == 'Personnel') {
      // Personnel Display
      if (this.configStampInner.language != 'gb') {
        this.setupDoubleLine()
      }
      else {
        this.configStampInner.display_name = (this.paramStampInner.stamp_title || '') + ' ' + this.paramStampInner.stamp_text
      }
    }
    else if (this.paramStampInner.stamp_type == 'Corporate') {
      // Corporate Display
      this.setupRotation()
    }
    else {
      // Upload Display
    }
  },
  methods: {
    setupRotation() {
      var vm = this
      let displayed_name = ''
      
      let txtnospace = vm.paramStampInner.stamp_text.replace(' ','')
      vm.configStampInner.str_length = txtnospace.length

      let center_count = parseInt(vm.configStampInner.str_length) / 2;
      let whitespace = "&#160;&#160;";
      
      for (let i=6; i<=(parseInt(center_count)); i++) {
        whitespace = whitespace + '&#160;'
      }

      vm.configStampInner.display_name = whitespace + txtnospace + whitespace
    },
    
    setupDoubleLine: function () {
      var vm = this
      let collect = ''
      let displayed_name = vm.paramStampInner.stamp_text.replace(' ', '');
      if (displayed_name.length < 4) {
        // 1 vertical line seal
        if (displayed_name.length == 1)
          collect = displayed_name
        else
          for (let character of displayed_name) 
            collect += character + '<br/>'
      }
      else {
        if (displayed_name.length%2 == 1)
          displayed_name = displayed_name + (this.configStampInner.language == 'kr' ? '인' : '印')

        let half_length = Math.ceil(displayed_name.length / 2)
        let top = displayed_name.substr(0,half_length)
        let bottom = displayed_name.substr(half_length)
        collect = top + '<br />' + bottom
      }
      
      vm.configStampInner.display_name = collect
    }
  }
};
</script>

<style lang="scss">
// @import "../../views/signaturestamp/Signature.scss"


// .signatures {
//   padding-left: 40px;
//   padding-right: 40px;
//   position: relative;
  
//   .listing-stamp {
//     // max-height: 200px !important; 
//     min-height: 180px !important; 
//     max-width: 450px !important; 
//     min-width: 145px !important;
//     width: 100% !important; 
//     height: 180px !important; 
//     padding: 20px 0 !important; 
//     margin: auto !important;
//   }

//   .sign-result {
//     border-radius: 5px;
//     border: solid 1px #ebebeb;
//     min-height: 235px;
//     color: #202020;
//     font-family: "Mrs Saint Delafield";
//     line-height: 1.51;
//     padding: 12px;
//     max-height: 235px;

//     display: flex;
//     justify-content: center;
//     flex-direction: column;
//     align-items: center;
//     height: 100%;
//     position: relative;
//     cursor: pointer;

//     img {
//       max-height: 140px;
//     }

//     .default-stamp {
//       font-size: 30px;
//       border-radius: 50%;
//       display: flex;
//       align-items: center;
//       justify-content: center;
//       word-break: keep-all;
//       width: 80px;
//       height: 80px;
//       text-align: center;
//     }
    
//     .gb-stamp {
//       font-family: Arial;
//       margin: auto;
//       color: #202020;
//       border: groove 3px;
//       border-right: #ebebeb;
//       border-left: #ebebeb;
//       border-radius: 5% !important;
//       width: -webkit-fill-available;
//       height: -webkit-fill-available;
//     }
//     .kr-stamp {
//       font-family: "Goyang";
//       color: #ff0000;
//       border: solid 3px #ff0000;
//       line-height: 30px;
//       width: auto !important;
//       height: auto !important;

//       mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
//       mask-size: 944px 604px;
//       mix-blend-mode: multiply;
//     }
//     .jp-stamp {
//       font-family: "crayon_1-1";
//       color: #ff0000;
//       border: solid 3px #ff0000;
//       line-height: 30px;
//       width: auto !important;
//       height: auto !important;
      

//       mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
//       mask-size: 944px 604px;
//       mix-blend-mode: multiply;

//       span {
//         padding: 20px;
//       }
//     }
//     .signature-text {
//       font-size: 39px;
//     }
//     .hr-bar {
//       border-top: solid 1px #ebebeb;
//       width: 100%;
//     }
//     .initials-text {
//       font-size: 60px;
//       height: 60px;
//     }
//     .check-box {
//       position: absolute;
//       left: 0;
//       bottom: 0;
//     }
//   }
//   .checked {
//     border-color: #ef634c;
//     border-width: 2px;
//     padding-top: 10px !important;
//     padding-bottom: 10px !important;
//   }
//   .left-button {
//     position: absolute;
//     left: 0px;
//     top: calc(50% - 11px);
//     font-size: 22px;
//   }
//   .right-button {
//     position: absolute;
//     right: 0px;
//     top: calc(50% - 11px);
//     font-size: 22px;
//   }
// }
</style>