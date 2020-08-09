//Set veriables
const card = document.querySelector('.cards');
const cards = document.querySelectorAll('.card');
const newGame = document.querySelector('#new_game');

//Set global variables
let card1='';
let card2='';
let counter=0;

cardColorInit(); // initialize random colors during page load

newGame.addEventListener('click',(e)=> {
    cardColorInit();
})

if(card) card.addEventListener('click', (e)=>{
    card2 = e.target; //assign selected card to card 2
    if ((!e.detail || e.detail === 1) && !card2.classList.contains('cards') && !card2.dataset.isOpen){ //only allow first click from the user
        card2.style.backgroundColor = newColors[e.target.dataset.id]; //assign background color based on the newColors array
        if (!card2.classList.contains('rotate')) card2.classList.toggle('rotate'); //rotate the card
        if (counter === 0){
            card1 = e.target;  //assign selected card as card1 for new pair selection
            counter ++; //increase counter
        } else {
            if(card1!=card2){ //check if cards are clicked 2
                cardCompare();
                // initialize variables
                counter =0;
                card1='';
            }
        }
    }
});

function cardColorInit(){
    let initColor = [];    //use array to store colors;
    for (myCard of cards){
        myCard.classList.add('isClose'); //set color to black with class isClose
        if (myCard.classList.contains('rotate')) myCard.classList.toggle('rotate') // remove class rotate
        myCard.style.removeProperty('background-color'); //remove background property
        delete myCard.dataset.isOpen; //delete any isOpen dataset
    }
    for (let i=0;i<5;i++){ 
        //select random rgb colors
        let red = Math.floor(Math.random() * 266);
        let green = Math.floor(Math.random() * 266);
        let blue = Math.floor(Math.random() * 266);
        initColor.push(`RGB(${red},${green},${blue})`); //push array twice
        initColor.push(`RGB(${red},${green},${blue})`); //push array twice
    }
    newColors = shuffle(initColor); //shuffle colors and assign to a variable
}

function shuffle(a){ 
    //shuffle data inside array
    for (let i=0;i<a.length;i++){
        const x = Math.floor(Math.random() * (i+1));
        [a[i],a[x]] =[a[x],a[i]];
    }
    return a;
}

function cardCompare(){
    const oldCard = card1.style.backgroundColor; //assign card color to variable
    const newCard = card2.style.backgroundColor; //assign card color to variable
    if (oldCard === newCard) {
        //add dataset isOpen if cards matched
        card1.dataset.isOpen = 'yes';
        card2.dataset.isOpen ='yes';
        card1.classList.remove('isClose'); //remove isClose property
        card2.classList.remove('isClose'); //remove isClose property
    } else {
        //hide cards after 1sec if card does not  match
        setTimeout(()=>{
            for (myCard of cards){
                if (!myCard.dataset.isOpen){
                    myCard.style.backgroundColor='RGB(0,0,0)';  //set cards back to black
                    if (myCard.classList.contains('rotate')) myCard.classList.toggle('rotate') // remove class rotate
                }
            }
        },1500); //set time out to 1second
    }
}