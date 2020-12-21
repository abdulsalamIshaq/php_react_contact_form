import React from 'react';
import axios from 'axios';
import logo from './logo.svg';
import './App.css';

class App extends React.Component {
	constructor(props) {
		super(props)
		this.state = {
			firstname: '',
			lastname: '',
			email: '',
			message: '',
			data: '',
		}
	}

	handleFormSubmit( e ) {
		e.preventDefault();

		//console.log(this.state);

		const url = 'http://localhost/react_contact/api/';

        axios({
            method: 'POST',
            url: url,
            data: this.state,
            headers: {
                'content-type': 'application/json',
                'Access-Control-Allow-Origin': '*'
            },
        })
        .then(res => {  

            this.setState({
       			data: res.data,
       			error: true
     		})
        })
	    .catch(error => console.error(error));
	}

	render() {
		return (
		    <div className="App">
		      	<div className="container">
		      		<h1>PHP React Contact Form</h1>

		      		{(this.state.data.sent) ? <h1 className="success-msg">Message sent successfully</h1>: ''}

		      		<form>

		      			<label htmlFor="firstname">First Name</label>
		      			<input type="text" id="firstname" className="firstname" 
		      				name="firstname" placeholder="First Name" value={this.state.firstname}
		      				onChange={e => this.setState({firstname: e.target.value})}
		      			/>

		      			<label htmlFor="firstname">Last Name</label>
		      			<input type="text" id="lastname" className="lastname" 
		      				name="lastname" placeholder="Last Name" value={this.state.lastname}
		      				onChange={e => this.setState({lastname: e.target.value})}
		      			/>

						<label htmlFor="firstname">Email</label>
		      			<input type="email" id="email" className="email" 
		      				name="email" placeholder="Email" value={this.state.email}
		      				onChange={e => this.setState({email: e.target.value})}
		      			/>

						<textarea name="" id="" cols="30" rows="6" value={this.state.message} 
						onChange={e => this.setState({message: e.target.value})}></textarea>

						<input type="submit" value="Submit" onClick={e => this.handleFormSubmit(e)}/>

		      		</form>
		      	</div>
		    </div>
		 );
	}
}


export default App;
