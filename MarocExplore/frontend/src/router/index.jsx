import { createBrowserRouter } from "react-router-dom";
import Home from "../pages/Home.jsx";
import Login from "../pages/Login.jsx";
import Register from "../pages/Register.jsx";
import Iteneraries from "../pages/Itenraries.jsx";
import NotFound from "../pages/NotFound.jsx";
import Createitinerary from "../pages/Createitinerary.jsx";
import DefaultLayout from "../layouts/DefaultLayout.jsx";


export const router = createBrowserRouter([
    {
        element: <DefaultLayout />,
        children: [
            {
                path: '/',
                element: <Home />
            },
            {
                path: '/login',
                element: <Login />
            },
            {
                path: '/register',
                element: <Register />
            },
            {
                path: '/iteneraries',
        
                element: <Iteneraries />
            },
            {
                path: '/createitinerary',
        
                element: <Createitinerary />
            },
            {
                path: '*',
        
                element: <NotFound />
            },
        ]
    }
])