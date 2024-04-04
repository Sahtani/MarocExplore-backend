import { Outlet } from "react-router-dom";
import Navbar from "../components/navbar";

export default function DefaultLayout() {
    return (
        <>
        <Navbar />
        <Outlet/>
        <footer>footer</footer>
        </>
    )
}