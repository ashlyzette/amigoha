let addTask = document.querySelector('#add_task');
let tasks = document.querySelector('#tasks');

loadTasks();

tasks.addEventListener('click', (e) => {
	const myClass = e.target.className;
	const todo = JSON.parse(localStorage.todo); //parse the current localStorage data
	let i = 0;
	let uid = '';
	if (myClass == 'fas fa-trash') {
		uid = e.target.parentNode.dataset.uid;
		e.target.parentNode.remove(); // remove from div
		//get the key index of the uid to be deleted
		for (i in todo) {
			if (todo[i].uid == uid) {
				todo.splice(i, 1); //remove the data from the todo array
				break;
			}
		}
	} else {
		if (myClass == 'task_item') {
			uid = e.target.dataset.uid;
			e.target.children[1].classList.toggle('task_complete'); //strike-through task if parent div is clicked
		}
		if (myClass == 'task_list') {
			uid = e.target.parentNode.dataset.uid;
			e.target.classList.toggle('task_complete'); //strike-through task if span element is clicked
		}
		if (myClass == 'task_list task_complete') {
			uid = e.target.parentNode.dataset.uid;
			e.target.classList.toggle('task_complete'); //strike-through task if span element is clicked
		}
		for (task of todo) {
			//loop through todo list and change task.isDone value
			if (task.uid === uid) {
				task.isDone === 'no' ? (task.isDone = 'yes') : (task.isDone = 'no');
				break;
			}
		}
	}
	localStorage.setItem('todo', JSON.stringify(todo)); //overwrite todo data in localStorage
});

addTask.addEventListener('keypress', (e) => {
	if (e.which === 13) {
		e.preventDefault();

		//Add task to div
		let newTask = document.createElement('div'); //create div element
		let uid = Date.parse(new Date().toLocaleString()); //create a unique id for localStorage
		newTask.innerHTML = `<i class="fas fa-trash" id='del'></i> <span class ='task_list'>${addTask.value}</span>`; //create html data to be appended
		newTask.classList.add('task_item'); //add the css class task_item
		newTask.dataset.uid = `${uid}`; //create data-uid using the uid generated
		tasks.append(newTask); //append new task to task div

		//Check if there are data in localStorage
		let myArray = []; //create a new array
		if (localStorage.todo) {
			let todo = JSON.parse(localStorage.todo); // load data to todo variable
			for (task of todo) {
				myArray.push(task); //push existing items to myarray
			}
		}
		//Create myTodo object to storage data input from user
		let myTodo = {
			uid: `${uid}`,
			task: `${addTask.value}`,
			isDone: `no`
		};
		myArray.push(myTodo); // push new data to myArray
		localStorage.setItem('todo', JSON.stringify(myArray)); //overwrite existing todo data
		addTask.value = ''; //clear add task input text
	}
});

function loadTasks() {
	if (localStorage.todo) {
		let todo = JSON.parse(localStorage.todo);
		for (task of todo) {
			let newTask = document.createElement('div'); //create div element
			let uid = task.uid; //create a unique id for localStorage
			if (task.isDone === 'no')
				newTask.innerHTML = `<i class="fas fa-trash" id='del'></i> <span class ='task_list'>${task.task}</span>`; //create html data to be appended with no task_complete class
			else
				newTask.innerHTML = `<i class="fas fa-trash" id='del'></i> <span class ='task_list task_complete'>${task.task}</span>`; //create html data to be appended with task_complete task
			newTask.classList.add('task_item'); //add the css class task_item
			newTask.dataset.uid = `${uid}`; //create data-uid using the uid generated
			tasks.append(newTask); //append new task to task div
		}
	}
}
