
import { Link, useNavigate } from "react-router-dom";
// import logo from '../assets/images/logo.png';
import {useRef, useState} from 'react';
import { axiosClient } from "../config/axiosClient";
export default function Register(){


    // const API_BASE_URL = import.meta.env.VITE_API_BASE_URL
    const [ errors, setErrors ] = useState(null)
    const navigate = useNavigate()
    const nameRef = useRef()
    const imageRef = useRef()
    const emailRef = useRef()
    const passwordRef = useRef()
    const passwordConfirmationRef = useRef()

    const handleSubmit = async (e) => {
        e.preventDefault()
        const registerData = {
            'name': nameRef.current.value,
            'image': imageRef.current.files[0],
            'email': emailRef.current.value,
            'password': passwordRef.current.value,
            'password_confirmation': passwordConfirmationRef.current.value
        }
        const formData = new FormData()
        formData.append('name', registerData.name)
        formData.append('image', registerData.image)
        formData.append('email', registerData.email)
        formData.append('password', registerData.password)
        formData.append('password_confirmation', registerData.password_confirmation)
        await axiosClient.post("/signup", formData)
        
        .then(({data}) => {
            console.log('hi');
            const [user,token] = data
            console.log(user);
            console.log(token)
            const date = new Date();
            date.setTime(date.getTime() + (7*24*60*60*1000));
            let expires = "expires=" + date.toUTCString();
            document.cookie = `ACCESS_TOKEN=${token};${expires};path=/`;
            navigate('/');
        }).catch(({response}) => {
            console.log(response.data);
            const {message, errors} = response.data
            if(errors) {
                setErrors(errors)
            }else {
                setErrors({message: message})
            }
            console.log(response.data)
        })

    // console.log(registerData)
}
return (
    <div className="login-page h-[30rem] relative overflow-y-scroll mt-8 scrollbar-hide pr-8 font-montserrat">
        <div className="content container mx-auto md:pl-20 grid gap-8 grid-cols-1 md:grid-cols-4 h-full relative w-full">
            <div className="intro-text col-span-2 rounded-lg md:bg-sign-up md:bg-cover hidden md:block md:order-4">
                <div className="intor-wrapper md:flex px-12 rounded-lg pb-8 h-full w-full bg-gray-500/25 md:flex-col md:items-start md:justify-end">
                    {/* <img src={logo} alt="weshare" className='h-32 mb-5' /> */}
                    <h1 className='text-white text-2xl'>Open and Share Your Thougths to the World.</h1>
                </div>
            </div>
            <div className="form-wrapper md:col-span-2 flex flex-col justify-center gap-4 pr-8">
                <h2 className='flex items-center flex-col md:flex-row'>Create Account & Inspire the world.</h2>
                <form className="w-full" onSubmit={handleSubmit} method="POST" encType="multipart/form-data">
                    {errors && errors.message && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{errors.message}</p>}
                    <div className="mb-6">
                        <label htmlFor="fullName" className="block mb-2 text-sm font-medium">Full Name</label>
                        <input type="text" id="fullName" ref={nameRef} className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="ex.:Jhon Doe" required />
                        {errors && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{errors.name}</p>}
                    </div>
                    <div className="mb-6"> 
                        <label className="block mb-2 text-sm font-medium" htmlFor="multiple_files">Upload Your Image</label>
                        <input className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="multiple_files" type="file" ref={imageRef} accept="image/png, image/jpeg" required />
                        {errors && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{errors.image}</p>}
                    </div>
                    <div className="mb-6">
                        <label htmlFor="email" className="block mb-2 text-sm font-medium">Your email</label>
                        <input type="email" id="email" ref={emailRef} className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@flowbite.com" required />
                        {errors && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{errors.email}</p>}
                    </div>
                    <div className="mb-6">
                        <label htmlFor="password" className="block mb-2 text-sm font-medium">Your password</label>
                        <input type="password" id="password" ref={passwordRef} className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        {errors && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{errors.password}</p>}
                    </div>
                    <div className="mb-6">
                        <label htmlFor="password_confirm" className="block mb-2 text-sm font-medium">Confirm Your password</label>
                        <input type="password" id="password_confirm" ref={passwordConfirmationRef} className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        {errors && <p className="mt-2 text-sm text-red-600 dark:text-red-500">{errors.password}</p>}
                    </div>
                    <button type="submit" className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </form>
                <p>Already Have an Account? <Link className='text-blue-500' to={"/login"}>Login.</Link></p>
            </div>
        </div>
    </div>
);
}


