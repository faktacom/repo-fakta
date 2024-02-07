import editor from './editor.js';
import axios from "axios";


class BankImage{

    baseUrl = window.location.origin;
    popupWindow = window.open('', 'Popup Window', 'width=800,height=600');
    imageContainer = this.popupWindow.document.createElement('div');

    static get toolbox(){
        return{
            title:'Bank Image',
            icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>'
        }
    }
    showPopupWindow(){
      this.popupWindow.document.body.innerHTML = '';
      this.popupWindow.document.body.style.fontFamily = 'Poppins,sans-serif';

      const headerContainer = this.popupWindow.document.createElement('div');
      headerContainer.style.display = 'flex';
      headerContainer.style.gap = '10px';
      headerContainer.style.paddingBottom = '5px';
      headerContainer.style.marginBottom = '5px';
      headerContainer.style.borderBottom = '1px solid black';

      const title = this.popupWindow.document.createElement('h1');
      title.innerText = 'Bank Image';
      title.style.textAlign = 'center';

      const searchInputContainer =  this.popupWindow.document.createElement('div');
      searchInputContainer.style.display = 'grid';
      searchInputContainer.style.alignItems= 'center';

      
      const searchInput = this.popupWindow.document.createElement('input');
      searchInput.placeholder = 'Search Image...';
      searchInput.style.padding = '10px';
      searchInput.style.border = '1px solid lightgray';

      searchInput.addEventListener('change', () => {
        this._searchImage(searchInput.value);
      });
      
      searchInputContainer.appendChild(searchInput);

      headerContainer.appendChild(searchInputContainer);
      headerContainer.appendChild(title);

      const imageBankList = this.showImage();
  
      this.popupWindow.document.body.appendChild(headerContainer);
      this.popupWindow.document.body.appendChild(imageBankList);
    }

    showImage(listBankImage = false){
      this.imageContainer.style.display = 'grid';
      this.imageContainer.style.gridTemplateColumns = 'repeat(4, 1fr)';
      this.imageContainer.style.gap = '10px';

      var imageList = listBankImageDefault;
      
      if(listBankImage){
        imageList = listBankImage;
        this.imageContainer.innerHTML = '';
      }

      imageList.forEach((image) => {
        const imgElement = this.popupWindow.document.createElement('img');
        imgElement.src = `${this.baseUrl}/assets/images/bank_image/${image.image_path}`;
        imgElement.style.width = '100px';
        imgElement.style.height = '100px';
        imgElement.style.objectFit = 'cover';

        const nameElement = this.popupWindow.document.createElement('p');
        nameElement.innerText = image.image_title;
  
        const imageCard = this.popupWindow.document.createElement('div');
        imageCard.style.display = 'flex';
        imageCard.style.flexDirection = 'column';
        imageCard.style.alignItems = 'center';
        imageCard.style.padding = '10px';
        imageCard.style.border = '1px solid lightgray'
  
        imageCard.appendChild(imgElement);
        imageCard.appendChild(nameElement);
        this.imageContainer.appendChild(imageCard);

        imageCard.addEventListener('click', () => {
          this._insertImageToEditor(imgElement.src);
        });

        imageCard.addEventListener('mouseenter', () => {
          imageCard.style.backgroundColor = 'lightgray';
          imageCard.style.border = '1px solid #b81f24'
          imageCard.style.cursor = 'pointer';
        });

        imageCard.addEventListener('mouseleave', () => {
          imageCard.style.backgroundColor = '';
          imageCard.style.border = ''
          imageCard.style.cursor = 'auto';
        });

      });

      return this.imageContainer;
    }

    _insertImageToEditor(imageUrl) {
      editor.blocks.insert('image', {
        file:{
          url: imageUrl,
        }
      });
      this.popupWindow.close();
    }

    _searchImage(imageName){
      axios({
        method: 'get',
        url: `${this.baseUrl}/admin/bank/search/${imageName}`
      }).then((response) => {
        var NewImageList = response.data.image_list;
        this.imageContainer.innerHTML = '';
        this.showImage(NewImageList);
      }).catch((error) => {
        console.log("Search image error :", error);
      });
    }

    render() {
      const popUpWindow = this.showPopupWindow();

      return popUpWindow;
    }  
}

export default BankImage;
