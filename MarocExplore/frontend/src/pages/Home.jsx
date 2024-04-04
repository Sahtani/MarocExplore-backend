import { useEffect, useState } from "react";
import { axiosClient } from "../config/axiosClient";

export default function Home(){

    const [itineraries, setItineraries] = useState([])

    useEffect(() => {
        const fetchData = async () => {
            await axiosClient.get("/itineraries").then(({data}) => {
                setItineraries(data.itineraries)
            }).catch((errors) => {
                console.log(errors);
            })
        }

        fetchData();
    }, [])
    return (
        <>
        {itineraries.map((itinerarie, index) => {
            return (
            <div key={index}>
                <img src={itinerarie.image} />
            </div>
            )
        })}
        </>
    )
}