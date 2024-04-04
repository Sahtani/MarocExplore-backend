import { useEffect } from "react"
import { axiosClient } from "../config/axiosClient"


export default function Iteneraries() {
    useEffect(() => {
        const fetchData = async () => {
            await axiosClient.get("/itineraries").then(response => {
                console.log(response);
            })
        }

        fetchData();
    }, [])

    return <>
        hi from iteneraries page
    </>

}