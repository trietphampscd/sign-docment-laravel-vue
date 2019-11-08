import { omit } from "lodash";

// config
const marginDefault = 15;
const resizeOption = (callback) => {
  return {
    maxHeight: 100,
    minHeight: 35,
    maxWidth: 200,
    minWidth: 145,
    stop(event, ui) {
      callback && callback(event, ui);
    }
  }
};

const zIndexDefault = 1111;
let userToolList = null;
let addCommentButton = null;
let firstPageLoading = true;
const DATA_COMMENT_TYPE = 'comments';
const DATA_TEXT_TYPE = 'text';

const droppableContent = `droppable_content_`;
const documentContainer = `document_container`;
const documentID = `doc_id_`;

const commentFormElement = (recipientsList, data = {}, annotation_id) => `
  <div class="hiddenCommentForm display_none">
    <form class="commentForm_${annotation_id} hiddenForm">
      <div class="form-group">
        <textarea type="email" class="form-control" name="comments" placeholder="Comment" rows="5" required>${data.text || ''}</textarea>
      </div>
      <div class="form-group multiselectForm">
        <select class="form-control multiselect" multiple="multiple" name="recipients">
          ${recipientsList.map(v => `<option value="${v.id}" ${data.recipient_arr && JSON.parse(`[${data.recipient_arr}]`).find(a => a === v.id) && 'selected'}>${v.name}</option>`)}
        </select>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
`;

const commentElement = (recipientsList, data, annotation_id) => `
  <div ${!annotation_id && `id="addCommentElement"`} data-type="${DATA_COMMENT_TYPE}" ${annotation_id && `data-annotation_id="${annotation_id}"`}>
    <i class="fa fa-comment" aria-hidden="true"></i>
    ${commentFormElement(recipientsList, data, annotation_id)}
  </div>  
`;

let _currentRotateDegree = 0;
const defaultJump = 90;

export function deleteSuccessHandle(docId, pageId) {
  console.log(docId, pageId)
  let _page_doc_nav_id = $(`#doc_nav_id_${docId}_${pageId}`);
  _page_doc_nav_id.parent().addClass('d-none');
  
  let _page_doc_id = $(`#${documentID}${docId}_${pageId}`);
  _page_doc_id.parent().addClass('d-none');
}

export function rotateFunction(location, docId, pageId, callback) {
  let _documentContainer = $(`#${documentContainer}`);
  let _documentContainerWidth = _documentContainer.width() - 10;

  let _imagesElement = $(`#${documentID}${docId}_${pageId} img`);
  location === 'left' ? _currentRotateDegree -= defaultJump : _currentRotateDegree += defaultJump;

  _currentRotateDegree > (defaultJump * 2) && (_currentRotateDegree = -defaultJump);
  _currentRotateDegree < -(defaultJump * 2) && (_currentRotateDegree = defaultJump);

  let _translate = _currentRotateDegree === defaultJump ? 'translateY(-20%)' : _currentRotateDegree === -defaultJump ? 'translateY(20%)' : '';
  let _height = (_currentRotateDegree === -defaultJump || _currentRotateDegree === defaultJump) ? _documentContainerWidth : 'auto';

  // console.log(_currentRotateDegree)
  _imagesElement.each(k => {
    let _image = $(_imagesElement[k]);
    _image.css({
      // display: 'block',
      // transformOrigin: 'top left',
      transform: `rotate(${_currentRotateDegree}deg) ${_translate}`,
      height: _height,
    });

    (_currentRotateDegree === -defaultJump || _currentRotateDegree === defaultJump) ? _image.addClass('w-auto') : _image.removeClass('w-auto');
  });

  // save data to server
  let sendData = {
    document_id: docId,
    page: pageId + 1,
    rotate: _currentRotateDegree,
    delete: 0,
  }
  callback(sendData);
}


export function addCommentToDocument(pagesNumber, recipientsList, callback) {
  userToolList = null;
  let defaultData = {
    text: '',
    recipient_arr: recipientsList.map(v => v.id).join(),
  }
  addCommentButton = commentElement(recipientsList, defaultData);

  $(document).ready(function () {

    // =======
    // click add tool
    // =======
    $(`div[id^="${droppableContent}"]`).click((e) => {

      if (addCommentButton) {

        const currentTarget = $(e.currentTarget);
        const page_num = currentTarget.data("page_num");
        const addNewToolToContent = $(addCommentButton).clone();
        let element_style = {
          position: 'absolute',
          left: e.originalEvent.layerX,
          top: e.originalEvent.layerY,
          zIndex: zIndexDefault,
        }
        addNewToolToContent.addClass("user-drag on-user-drag drag-success user-can-delete addCommentElement");
        addNewToolToContent.css(element_style);
        addNewToolToContent.removeAttr('id');
        addNewToolToContent.appendTo(currentTarget)

        addNewToolToContent.removeClass(
          "on-user-drag ui-draggable ui-draggable-handle ui-draggable-dragging ui-resizable defaultResize"
        );

        addNewToolToContent.draggable({
          cursor: "move",
          revert: "invalid",
          zIndex: zIndexDefault,
          delay: 200,
        });

        // save position
        sendCallback(addNewToolToContent, page_num, DATA_COMMENT_TYPE, callback, recipientsList.map(v => v.id).join());

        // Toggle Comment Form 
        handleCommentForm(addNewToolToContent, callback);

        // remove active class tool
        addCommentButton = null;

        const checkHasElement = $(`#${documentContainer} .temporaryTool`);
        checkHasElement.remove();
      }
    });

    // ========
    // add tool to mouse when tool available
    // ========
    const $droppableContent = $(`div[id^="${droppableContent}"]`);
    const $documentContainer = $(`#${documentContainer}`);
    $droppableContent.on('mousemove', function (e) {
      if (addCommentButton) {
        const addNewToolToContent = $(addCommentButton).clone();
        let element_style = {
          position: 'absolute',
          left: e.originalEvent.pageX - $documentContainer.offset().left + marginDefault,
          top: e.originalEvent.pageY - $documentContainer.offset().top + marginDefault,
          zIndex: zIndexDefault,
        }
        const checkHasElement = $(`#${documentContainer} #${$(addNewToolToContent).attr('id')}`);
        checkHasElement.length === 0 && addNewToolToContent.appendTo($(`#${documentContainer}`));
        $(checkHasElement).css(element_style);
        $(checkHasElement).addClass('temporaryTool');

        $droppableContent.mouseout(() => {
          const checkHasElement = $(`#${documentContainer} .temporaryTool`);
          checkHasElement.remove();
        });
      }
    });

  });
}

export function generalDefaultButton2(annotations, recipientsList, callback) {
  annotations.map((annotation, key) => {
    if (annotation.type === DATA_TEXT_TYPE) {
      // left button
      let recipient = recipientsList.filter(v => annotation.actor_id === v.id);
      annotation.actor = recipient && recipient.length > 0 && recipient[0];
      let tool = prepareTools.find(v => v.name === annotation.type_tools);
      const page_num = annotation.page_num;
      let _dropableContentId = `${droppableContent}${page_num}`;

      let element_style = `position: absolute; color: ${annotation.actor.color}; background: #fff; border: 1px solid ${annotation.actor.color}; left: ${annotation.pos_x}px; top: ${annotation.pos_y}px; z-index: 1111; width: 143px; height: 31px;`;

      let new_element = `
        <span
          data-color='${annotation.actor && annotation.actor.color}'
          data-tool='${addDataToElement(annotation.creator_id, annotation.actor, tool)}'
          data-annotation_id='${annotation.id}'
          class='user-drag drag-success user-can-delete tool-sign tool_sign_${annotation.id}'
          style='${element_style}'
        >
          <i class="${tool.icon}" />
          <span>${tool.label}</span>
        </span>
      `;
      new_element = $($.parseHTML(new_element)[1]);

      // new_element.detach().appendTo(`#${_dropableContentId}`);
      console.log('annotation.type', _dropableContentId)
      $("<span>geeks Writer !!!</span>").appendTo(`#${_dropableContentId}`);

      new_element.resizable().resizable('destroy');
      new_element.resizable(resizeOption(() => {
        sendCallback(new_element, page_num, callback);
      }));
    } else if (annotation.type === DATA_COMMENT_TYPE) {
      // comment button
      const page_num = annotation.page_num;
      let _dropableContentId = `${droppableContent}${page_num}`;
      let new_element = commentElement(recipientsList, annotation, annotation.id);

      new_element = $($.parseHTML(new_element)[1]);
      let element_style = {
        position: 'absolute',
        left: annotation.pos_x,
        top: annotation.pos_y,
        zIndex: zIndexDefault,
      }

      new_element.addClass(`user-drag drag-success user-can-delete tool-sign tool_sign_${annotation.id} addCommentElement`);
      new_element.css(element_style);
      new_element.detach().appendTo(`#${_dropableContentId}`)

      // Toggle Comment Form 
      handleCommentForm(new_element, callback);

    }
  });
}

export function generalDefaultButton(annotations, recipientsList) {
  $(document).ready(function () {
    annotations.map((annotation, key) => {
      let recipient = recipientsList.filter(v => annotation.actor_id === v.id);
      annotation.actor = recipient && recipient.length > 0 && recipient[0];
      let tool = prepareTools.find(v => v.name === annotation.type_tools);
      const page_num = annotation.page_num;
      let _dropableContentId = `${droppableContent}${page_num}`;
      let element_style = `position: absolute; color: ${annotation.actor.color}; background: #fff; border: 1px solid ${annotation.actor.color}; left: ${annotation.pos_x}px; top: ${annotation.pos_y}px; z-index: 1111; width: 143px; height: 31px;`;

      let new_element = `
        <span
          data-color='${annotation.actor && annotation.actor.color}'
          data-tool='${addDataToElement(annotation.creator_id, annotation.actor, tool)}'
          data-annotation_id='${annotation.id}'
          class='user-drag drag-success user-can-delete tool-sign tool_sign_${annotation.id}'
          style='${element_style}'
        >
          <i class="${tool.icon}" />
          <span>${tool.label}</span>
        </span>
      `;
      new_element = $($.parseHTML(new_element)[1]);

      new_element.detach().appendTo(`#${_dropableContentId}`);
      // console.log('_dropableContentId_______',  _dropableContentId)
      // $("<span>geeks Writer !!!</span>").appendTo(`#${_dropableContentId}`);
    })
  })
}

export function unredoButton(id, type) {
  type === "undo" ? $(".tool_sign_" + id).hide() : $(".tool_sign_" + id).show()
}

function addDataToElement(creator_id, item, tool) {
  return JSON.stringify({ ...omit(item, ["children", "pivot", "phone", "password", "created_at", "updated_at"]), tool: { ...tool }, clientId: creator_id });
}

export function initialPrepare(pages) {
  $(document).ready(function () {
    pages.map((page, index) => {
      let _page_doc_id = `#${documentID}${page.docId}_${page.pageNum}`;
      let _page_doc_nav_id_ = `#doc_nav_id_${page.docId}_${page.pageNum}`;
      if (firstPageLoading) {
        // =======
        // loading image
        // =======

        // loader content page
        $(`${_page_doc_id} .loader_img`).show();
        $(`${_page_doc_id} .main_img`).on('load', function () {
          $(`${_page_doc_id} .loader_img`).hide();
        });
        // loader nav page
        $(`${_page_doc_nav_id_} .loader_img`).show();
        $(`${_page_doc_nav_id_} .main_img`).on('load', function () {
          $(`${_page_doc_nav_id_} .loader_img`).hide();
        });
        // end loading image
      } else {
        $(`${_page_doc_id} .loader_img`).hide();
        $(`${_page_doc_nav_id_} .loader_img`).hide();
      }

      // default rotate page
      let _documentContainer = $(`#${documentContainer}`);
      let _documentContainerWidth = _documentContainer.width() - 10;
      let _docIdElement = $(`#${documentID}${page.docId}_${page.pageNum}`);
      const defaultRotate = _docIdElement.parent().data('rotate') || 0;
      let _height = (defaultRotate === -defaultJump || defaultRotate === defaultJump) ? _documentContainerWidth : 'auto';
      let _translate = defaultRotate === defaultJump ? 'translateY(-20%)' : defaultRotate === -defaultJump ? 'translateY(20%)' : '';
      let _imagesElement = _docIdElement.children('img');
      _imagesElement.css({
        transform: `rotate(${defaultRotate}deg) ${_translate}`,
        height: _height,
      });
      (defaultRotate === -defaultJump || defaultRotate === defaultJump) ? _imagesElement.addClass('w-auto') : _imagesElement.removeClass('w-auto');

    });


    firstPageLoading = false;
  });
}

export function prepareHandle(pagesNumber, recipientsList, callback) {
  $(document).ready(function () {
    // =======
    // click add tool
    // =======
    $(`div[id^="${droppableContent}"]`).click((e) => {
      if (userToolList) {
        const currentTarget = $(e.currentTarget);
        const page_num = currentTarget.data("page_num");
        const addNewToolToContent = $(userToolList).clone();
        let element_style = {
          position: 'absolute',
          color: addNewToolToContent.data("color"),
          border: `1px solid ${addNewToolToContent.data("color")}`,
          background: "#fff",
          left: e.originalEvent.layerX,
          top: e.originalEvent.layerY + marginDefault,
          zIndex: zIndexDefault,
        }
        addNewToolToContent.addClass("user-drag on-user-drag drag-success user-can-delete");
        addNewToolToContent.css(element_style);
        addNewToolToContent.removeAttr('id');
        addNewToolToContent.appendTo(currentTarget);
        appendElementToDoc(addNewToolToContent, page_num, DATA_TEXT_TYPE, callback);

        // remove active class tool
        let oldToolList = $(userToolList).parent();
        if (oldToolList.length > 0 && $(oldToolList) && $(oldToolList).hasClass('active')) {
          $(oldToolList).removeClass('active');
          userToolList = null;
        }

        const checkHasElement = $(`#${documentContainer} .temporaryTool`);
        checkHasElement.remove();
      }
    });

    // ========
    // add tool to mouse when tool available
    // ========
    const $droppableContent = $(`div[id^="${droppableContent}"]`);
    const $documentContainer = $(`#${documentContainer}`);
    $droppableContent.on('mousemove', function (e) {
      if (userToolList) {

        const addNewToolToContent = $(userToolList).clone();
        let element_style = {
          position: 'absolute',
          color: addNewToolToContent.data("color"),
          border: `1px solid ${addNewToolToContent.data("color")}`,
          background: '#fff',
          left: e.originalEvent.pageX - $documentContainer.offset().left + marginDefault,
          top: e.originalEvent.pageY - $documentContainer.offset().top + marginDefault,
          // left: e.originalEvent.layerX + marginDefault,
          // top: e.originalEvent.layerY + marginDefault,
          zIndex: zIndexDefault,
          padding: '1px 6px',
          borderRadius: 3,
          opacity: 0.5,
          transition: '0.01s',
        }
        const checkHasElement = $(`#${documentContainer} #${$(addNewToolToContent).attr('id')}`);
        checkHasElement.length === 0 && addNewToolToContent.appendTo($(`#${documentContainer}`));
        $(checkHasElement).css(element_style);
        $(checkHasElement).addClass('temporaryTool');

        $droppableContent.mouseout(() => {
          const checkHasElement = $(`#${documentContainer} .temporaryTool`);
          checkHasElement.remove();
        });

      }
    });

    // ========
    // dropable tool
    // ========
    addUserDrag(pagesNumber, recipientsList, callback)

  });
}

function addUserDrag(pagesNumber, recipientsList, callback) {
  pagesNumber && pagesNumber.length > 0 && pagesNumber.map(page_num => {

    page_num = page_num + 1;

    //Make element draggable
    let x = null, _dropableContentId = `${droppableContent}${page_num}`;
    $(`#${_dropableContentId}`).droppable({
      accept: ".user-drag",
      activeClass: "user-drop-area",
      out: function (event, ui) {
        const _getType = ui.helper.data("type");
        if (_getType !== DATA_COMMENT_TYPE) {
          $(ui.helper).css({ "color": "#555", background: "#fff", border: `1px solid #999` });
        }
      },
      over: function (event, ui) {
        let _getColor = ui.helper.data("color");
        const _getType = ui.helper.data("type");
        if (_getType !== DATA_COMMENT_TYPE) {
          $(ui.helper).css({ "color": _getColor, background: "#fff", border: `1px solid ${_getColor}` });
        }
      },
      drop: function (e, ui) {
        const _uiDraggableId = $(ui.draggable)[0].id;

        // change position
        let x = ui.helper.clone();
        let _getLeftElement = x.css("left").replace("px", "") || 0,
          _getTopElement = x.css("top").replace("px", "") || 0;
        _getLeftElement = parseInt(_getLeftElement);
        _getTopElement = parseInt(_getTopElement);

        let __top = ui.offset.top - $(this).offset().top;
        x.css("left", Math.abs((_uiDraggableId ? 200 : 0) - _getLeftElement));
        x.css("top", Math.abs(__top));

        // first time
        if (_uiDraggableId !== "") {

          ui.helper.remove();

          // add draggable, resize 
          appendElementToDoc(x, page_num, DATA_TEXT_TYPE, callback);

        }
        // second time
        else {
          const _getType = ui.helper.data("type");

          ui.helper.remove();
          $(ui.draggable).remove();

          // add draggable, resize 
          let _data_type = _getType === DATA_COMMENT_TYPE ? DATA_COMMENT_TYPE : DATA_TEXT_TYPE;
          appendElementToDoc(x, page_num, _data_type, callback, true);

          // Toggle Comment Form 
          if (_getType === DATA_COMMENT_TYPE) {
            console.log('comment move')
            console.log(x.data('annotation_id'))
            x.append(commentFormElement(recipientsList, x.data('annotation_id')))
            hiddenCommentFormFunc();
            handleCommentForm(x, callback, true);
          }
        }
      }
    });
  });
}

function appendElementToDoc(x, page_num, type, callback, isSecondTime = false) {
  let _dropableContentId = `${droppableContent}${page_num}`;

  x.removeClass(
    "on-user-drag ui-draggable ui-draggable-handle ui-draggable-dragging ui-resizable defaultResize"
  );

  x.addClass("drag-success user-can-delete");

  x.draggable({
    cursor: "move",
    revert: "invalid",
    zIndex: zIndexDefault,
    delay: 200,
    stop: function (event, ui) {
      let _getColor = ui.helper.data("color");
      $(ui.helper).css({ "color": _getColor, background: "#fff", border: `1px solid ${_getColor}` });
      $(".remove-user-drag").removeClass('remove-user-drag-hover');
    },
    drag: function (event, ui) {
      $(ui.helper).addClass("on-user-drag");
      $(".remove-user-drag").addClass('remove-user-drag-hover ');
    },
  });

  type === DATA_TEXT_TYPE && x.resizable(resizeOption(() => {
    sendCallback(x, page_num, type, callback);
  }));


  x.detach().appendTo(`#${_dropableContentId}`);

  if (isSecondTime && type === DATA_TEXT_TYPE) {
    x.resizable('destroy');
    x.resizable(resizeOption(() => {
      sendCallback(x, page_num, type, callback);
    }));
  }

  sendCallback(x, page_num, type, callback);

}

function sendCallback(x, page_num, type, callback, recipient_arr, text) {
  let _dropableContentId = $(`#${droppableContent}${page_num}`);
  let _getDataTool = x.data("tool") || {},
    _getDataAnnotationId = x.data("annotation_id"),
    _getPosition = x.position(),
    _getWidth = x.width() + 20,
    _getHeight = x.height() + 10,
    _getPageWidth = _dropableContentId.first().width(),
    _getPageHeight = _dropableContentId.first().height();

  callback({
    ..._getDataTool,
    annotation_id: _getDataAnnotationId || null,
    send_data: {
      actor_id: _getDataTool.id || 0,
      page_num: page_num,
      doc_id: _dropableContentId.data("doc_id"),
      width: _getPageWidth && _getPageWidth.toFixed(0),
      height: _getPageHeight && _getPageHeight.toFixed(0),
      pos_x: _getPosition.left && _getPosition.left.toFixed(0),
      pos_y: _getPosition.top && _getPosition.top.toFixed(0),
      size_w: _getWidth && _getWidth.toFixed(0),
      size_h: _getHeight && _getHeight.toFixed(0),
      alpha: 1,
      text: text,
      font_family: '',
      font_size: 12,
      font_weight: 'N',
      font_color: '000000',
      type_tools: _getDataTool.tool && _getDataTool.tool.name || type,
      type: type,
      recipient_arr: recipient_arr,
      // creator_id: _getDataTool.clientId,
    }
  }, x);
}

function hiddenCommentFormFunc() {
  $(`.popover`).popover('hide');
  $(`.addCommentElement`).css({ 'opacity': 0.5, zIndex: zIndexDefault })
}


function handleCommentForm(parentElement, callback, isSecondTime = false) {
  let annotation_id = parentElement.data("annotation_id")

  parentElement.popover({
    html: true,
    container: parentElement.parent(),
    content: parentElement.children('.hiddenCommentForm')//.clone(),
  });

  $(`div[id^="${droppableContent}"] .addCommentElement`).on("click", e => {
    let currentTarget = $(e.currentTarget);
    currentTarget.css({ 'opacity': 1, zIndex: 9999 });
  });

  // add multiselect
  if (!isSecondTime) {
    $(`.commentForm_${annotation_id} .multiselect`)
      .multiselect({
        allSelectedText: 'All Recipients',
        includeSelectAllOption: true,
      })
  }

  // submit comment
  $(`.commentForm_${annotation_id}`).on('submit', (e) => {
    let formValue = $(`.commentForm_${annotation_id}`).serializeArray();
    let newValue = mergeArraySameObjectValue(formValue);
    const page_num = parentElement.parent().data("page_num");

    sendCallback(parentElement, page_num, DATA_COMMENT_TYPE, callback, newValue[1].value.join(), newValue[0].value.join())

    e.preventDefault();
  });

}


export function userSideBarHandle(callback) {

  $(document).ready(function () {

    let userDrag = $(".user-item .tool");
    userDrag.click(e => {
      userDrag.hasClass('active') && userDrag.removeClass('active');

      // remove or add active class
      let currentTarget = $(e.currentTarget);
      if (!currentTarget.hasClass('active')) {
        currentTarget.addClass("active");
      } else {
        currentTarget.removeClass("active");
        userToolList = null;
      }

      // set copy when click
      if (currentTarget.children() && currentTarget.children().length > 0) {
        userToolList = currentTarget.children()[0];
        addCommentButton = null;
      }
    });


    // pagesNumber && pagesNumber.length > 0 && pagesNumber.map(page_num => {
    // let _dropableContentId = `${droppableContent}${page_num}`;
    //Make element draggable

    $(".user-drag").draggable({
      helper: "clone",
      cursor: "move",
      tolerance: "fit",
      zIndex: zIndexDefault,
      // containment: "div[id^='droppable_content_']",
      // containment: "div[id^='droppable_content_']",
      drag: function (event, ui) {
        $(ui.helper).addClass("on-user-drag");
        userToolList = null;
      }
    });

    $(".remove-user-drag").droppable({
      drop: function (event, ui) {
        let x = ui.helper.clone();
        userToolList = null;
        callback(x.data("annotation_id"), ui.draggable);
        // $(ui.draggable).remove();
      },
      // hoverClass: "remove-user-drag-hover",
      accept: ".user-can-delete"
    });
    // });
  });
};

export const prepareTools = [
  { name: "stamp", label: "Stamp", icon: "fas fa-stamp" },
  { name: "signature", label: "Signature", icon: "fa fa-pencil" },
  { name: "full_name", label: "Full Name", icon: "fa fa-user" },
  { name: "company", label: "Company", icon: "fa fa-building" },
  { name: "title", label: "Title", icon: "fa fa-briefcase" },
  { name: "text", label: "Text", icon: "fa fa-file-text" },
  { name: "date_signed", label: "Date Signed", icon: "fa fa-calendar" },
  { name: "attachments", label: "Attachments", icon: "fa fa-paperclip" }
];

var restore_state = []
var redo_list = []
var undo_list = []

export const history = {
  restore_state,
  redo_list,
  undo_list,
  saveState: function (restore_state, list, keep_redo) {
    keep_redo = keep_redo || false;
    if (!keep_redo) {
      this.redo_list = [];
    }

    (list || this.undo_list).push(restore_state);
  },
  undo: function (data) {
    this.undo_list = data;
    this.restoreState(this.undo_list, this.redo_list);
  },
  redo: function (data = null) {
    // if(data) {
    //   this.undo_list = data;
    //   this.restore_state = data.pop();
    //   this.redo_list = []
    // }
    this.restoreState(this.redo_list, this.undo_list);
  },
  restoreState: function (pop, push) {
    if (pop.length) {
      this.restore_state = pop.pop();
      this.saveState(this.restore_state, push, true);
    }
  }
}

// $('body').on('click', function (e) {
//   //did not click a popover toggle or popover
//   if ($(e.target).data('toggle') !== 'popover'
//     && $(e.target).parents('.popover.in').length === 0) {
//     // $('.addCommentElement').popover('hide');
//   }
// });

function mergeArraySameObjectValue(array) {
  let output = [];
  array.forEach(function (item) {
    let existing = output.filter(function (v, i) {
      return v.name == item.name;
    });
    if (existing.length) {
      let existingIndex = output.indexOf(existing[0]);
      output[existingIndex].value = output[existingIndex].value.concat(item.value);
    } else {
      if (typeof item.value == 'string')
        item.value = [item.value];
      output.push(item);
    }
  });
  return output;
}