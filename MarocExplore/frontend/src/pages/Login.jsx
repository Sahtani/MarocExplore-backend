import { useRef, useState } from "react";
import { Link, Navigate, useNavigate } from "react-router-dom";
import { axiosClient } from "../config/axiosClient";
import getCookie from "../helper/cookie";

export default function Login(){
    const email = useRef();
    const password = useRef()
    const [errors,setErrors] = useState(null);

    // creates a function to change location
    const navigate = useNavigate()

    // get token from cookie
    const token = getCookie('ACCESS_TOKEN')
    
    // check if token exist then retrun to home page else proceed and show login page
    if(token) {
        return <Navigate to="/" />;
    }

    async function handleLogin(e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append("email", email.current.value)
        formData.append("password", password.current.value)

        await axiosClient.post("/login", formData).then(({data}) => {
            const [user, token] = data
            console.log(user);
            const {original} = token

            // inserts the token into the cookies in the browser
            const date = new Date();
            date.setTime(date.getTime() + (48 * 60 * 60 * 1000));
            let expires = "expires=" + date.toUTCString();
            document.cookie = `ACCESS_TOKEN=${original.access_token};${expires};path=/`;

            // nvigate to home page after success login
            navigate('/');
        }).catch(({response})=>{
            setErrors(response.data)
        })
    }


    return (

        <>
        
        <div className="flex items-center justify-center mt-10">
        <div className=" w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
            <p className="text-red-500">{errors &&  errors.message }</p>
            <form className="space-y-6" onSubmit={handleLogin}>
                <h5 className="text-xl font-medium text-gray-900 dark:text-white">Sign in to our platform</h5>
                <div>
                    <label htmlFor="email" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                    <input type="email" ref={email} name="email" id="email" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required />
                </div>
                <div>
                    <label htmlFor="password" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                    <input type="password" ref={password} name="password" id="password" placeholder="••••••••" className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                </div>
                <button type="submit" className="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login to your account</button>
                <div className="text-sm font-medium text-gray-500 dark:text-gray-300">
                    Not registered? <Link to="/register" className="text-blue-700 hover:underline dark:text-blue-500">Create account</Link>
                </div>
            </form>
        </div>
        </div>


        </>
    )
   
}