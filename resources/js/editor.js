import EditorJS from "@editorjs/editorjs";
import ImageTool from '@editorjs/image';
import List from '@editorjs/list';
import Embed from '@editorjs/embed';
import Table from '@editorjs/table';
import Underline from '@editorjs/underline';
import axios from "axios";
import BankImage from './bankImagePlugin.js';
import BacaJuga from './bacaJugaPlugin.js';
const Header = require('@editorjs/header');
const LinkTool = require('@editorjs/link');
const SimpleImage = require('@editorjs/simple-image');
const Checklist = require('@editorjs/checklist');
const Quote = require('@editorjs/quote');
const Paragraph = require('editorjs-paragraph-with-alignment');
const InlineCode = require('@editorjs/inline-code');
const Marker = require('@editorjs/marker');
const RawTool = require('@editorjs/raw');




var saveBtn = document.getElementById('saveData');
var formPost = document.getElementById('formPost');
var imageUpload = document.getElementById('editorjs');
var imageUploadUrl = imageUpload.dataset.image_upload;
var linkUploadUrl = imageUpload.dataset.link_upload;

var formData = new FormData();
var title = document.getElementById('title');
var caption = document.getElementById('caption');
var newsTypeId = document.getElementById('typeOption');
var showDate = document.getElementById('showDate');
var showTime = document.getElementById('showTime');
var featuredImage = document.getElementById('featuredImage');
var image = document.getElementById('image');
var description = document.getElementById('description');
var categoryId = document.getElementById('categoryId');
var tagsOptions = document.getElementById('tags');


var premiumContent = document.getElementById('premiumContent');

if(isEdit){
  var editorContent = JSON.parse(detailNews.content);
  var formEdit = document.getElementById('formEdit');
  var editBtn = document.getElementById('editData');
}




const editor = new EditorJS({
    /**
     * Id of Element that should contain Editor instance
     */
    holder: 'editorjs',
    inlineToolbar:['bold','italic','link'],
    tools: {
      header: {
        class: Header,
        inlineToolbar: true,
      },
      paragraph: {
        class: Paragraph,
        inlineToolbar: true,
      },
      // linktool: {
      //   class: LinkTool,
      //   inlineToolbar:true,
      //   config: {
      //     endpoint: linkUploadUrl, // Your backend endpoint for url data fetching,
      //   }
      // },
      simple_image: SimpleImage,
      image: {
        class: ImageTool,
        config: {
          endpoints: {
            byFile: imageUploadUrl,
            byUrl: imageUploadUrl,
          }
        }
      },
      checklist: {
          class: Checklist,
          inlineToolbar: true,
      },
      list: {
          class: List,
          inlineToolbar: true,
      },
      embed: {
          class: Embed,
      },
      quote: {
        class: Quote,
        inlineToolbar: true,
      },
      table: {
        class: Table,
        inlineToolbar: true,
        config: {
          withHeadings: true
        }
      },
      inlineCode: {
        class: InlineCode,
      },
      marker: {
        class: Marker,
        inlineToolbar: true,
      },
      underline: {
        class: Underline,
      },
     
      raw: RawTool,
      bankimage: {
        class: BankImage,
      },
      bacaJuga:{
        class: BacaJuga, 
      },
    },
    onReady :()=>{
      if(isEdit){
        editor.render(editorContent);
      }
    },
    onChange: function() {
      if(isEdit){
        editor.save().then(function(outputData) {
          document.getElementById('contentAutosave').value = JSON.stringify(outputData);
        });
      }
    } 
});

function CheckFieldEmpty(value, field){
  if(value == "" || value === undefined || value == 0){
    console.log("test123");
    Swal.fire({
      icon: 'warning',
      title: field+' Empty',
      text: 'Please fill '+field+' fields',
      timer: 1000, 
      timerProgressBar: true, 
      showCancelButton: false,
      showConfirmButton: false
    });
    return true;
  }
  return false;
}

if(saveBtn){
  saveBtn.addEventListener('click', (e) =>{
    e.preventDefault();
    var url = formPost.action;
    var linkVideo = document.getElementById('linkVideo');
    var titleValue = title.value;
    if(CheckFieldEmpty(titleValue, "title")){
      return;
    }

    var captionValue = caption.value;
    if(CheckFieldEmpty(captionValue, "caption")){
      return;
    }
    var newsTypeIdValue = newsTypeId.value;
    if(CheckFieldEmpty(newsTypeIdValue, "news type id")){
      return;
    }
    if(linkVideo != null){
      var linkVideoValue = linkVideo.value
    }

    var showDateValue = showDate.value;
    if(CheckFieldEmpty(showDateValue, "show date")){
      return;
    }
    var showTimeValue = showTime.value;
    if(CheckFieldEmpty(showTimeValue, "show date")){
      return;
    }
    var featuredImageValue = featuredImage.value;
    if(CheckFieldEmpty(featuredImageValue, "featured image")){
      return;
    }
    var imageValue = image.value;
    if(CheckFieldEmpty(imageValue, "thumbnail")){
      return;
    }
    var descriptionValue = description.value;
    if(CheckFieldEmpty(descriptionValue, "description")){
      return;
    }
    var categoryIdValue = categoryId.value;
    if(CheckFieldEmpty(categoryIdValue, "category id")){
      return;
    }
    var tags = [];
    for(var i = 0; i < tagsOptions.selectedOptions.length; i++){
      tags.push(tagsOptions.selectedOptions[i].value);
    }
    if(CheckFieldEmpty(tags.length, "tags")){
      return;
    }
    var premiumContentValue = premiumContent.checked;
      
    editor.save().then((outputData) => {
      if(CheckFieldEmpty(outputData.blocks.length, "content")){
        return;
      }
      formData.append('title', titleValue);
      formData.append('caption', captionValue);
      formData.append('news_type_id', newsTypeIdValue);
      if(linkVideo){
        formData.append('link_video', linkVideoValue);
      }
      formData.append('show_date', showDateValue);
      formData.append('show_time', showTimeValue);
      formData.append('content', JSON.stringify(outputData));
      formData.append('featured_image', featuredImageValue);
      formData.append('image', imageValue);
      formData.append('description', descriptionValue);
      formData.append('category_id', categoryIdValue);
      tags.forEach(tag =>{
        formData.append('tags[]', tag);
      });
      formData.append('premium_content', premiumContentValue);
      axios({
        method: 'post',
        url: url,
        data: formData
      }).then((response) =>{
          if(response.data.valid === true){
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: response.data.message,
              timer: 3000, 
              timerProgressBar: true, 
              showCancelButton: false,
              showConfirmButton: false
            });
            location.href = response.data.url;
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response.data.message,
              timer: 3000, 
              timerProgressBar: true, 
              showCancelButton: false,
              showConfirmButton: false
            });
          }
      }).catch((error) =>{
        console.log("Saving error :", error);
      });
    }).catch((error) => {
      console.log('Saving from editor failed: ', error)
    });
  }, false)
}

if(editBtn){
  editBtn.addEventListener('click', (e) =>{
    e.preventDefault();
    var url = formEdit.action;
    var save = document.getElementById('save');

    var titleValue = title.value;
    if(CheckFieldEmpty(titleValue, "title")){
      return;
    }

    var captionValue = caption.value;

    var newsTypeIdValue = newsTypeId.value;

    var linkVideoValue = document.getElementById('linkVideo').value;

    var showDateValue = showDate.value;
    if(CheckFieldEmpty(showDateValue, "show date")){
      return;
    }
    var showTimeValue = showTime.value;
    if(CheckFieldEmpty(showTimeValue, "show date")){
      return;
    }
    var featuredImageValue = featuredImage.value;

    var imageValue = image.value;

    var descriptionValue = description.value;
    if(CheckFieldEmpty(descriptionValue, "description")){
      return;
    }
    var categoryIdValue = categoryId.value;
    if(CheckFieldEmpty(categoryIdValue, "category id")){
      return;
    }
    var tags = [];
    for(var i = 0; i < tagsOptions.selectedOptions.length; i++){
      tags.push(tagsOptions.selectedOptions[i].value);
    }
    if(CheckFieldEmpty(tags.length, "tags")){
      return;
    }
    var premiumContentValue = premiumContent.checked;
    var saveValue = save.value;

    editor.save().then((outputData) => {
      if(CheckFieldEmpty(outputData.blocks.length, "content")){
        return;
      }
      formData.append('title', titleValue);
      formData.append('caption', captionValue);
      formData.append('news_type_id', newsTypeIdValue);
      formData.append('link_video', linkVideoValue);
      formData.append('show_date', showDateValue);
      formData.append('show_time', showTimeValue);
      formData.append('content', JSON.stringify(outputData));
      formData.append('featured_image', featuredImageValue);
      formData.append('image', imageValue);
      formData.append('description', descriptionValue);
      formData.append('category_id', categoryIdValue);
      tags.forEach(tag =>{
        formData.append('tags[]', tag);
      });
      formData.append('premium_content', premiumContentValue);
      formData.append('is_axios', true);
      formData.append('save', saveValue);
      axios({
        method: 'post',
        url: url,
        data: formData
      }).then((response) =>{
        console.log(response);
        if(response.data.valid === true){
          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: response.data.message,
            timer: 3000, 
            timerProgressBar: true, 
            showCancelButton: false,
            showConfirmButton: false
          });
          location.href = response.data.url;
        }else{
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: response.data.message,
            timer: 3000, 
            timerProgressBar: true, 
            showCancelButton: false,
            showConfirmButton: false
          });
        }
      }).catch((error) => {
        console.log("Edit error :", error);
      });
    }).catch((error) => {
      console.log('Saving from editor failed: ', error)
    });

  }, false)
}



export default editor;
