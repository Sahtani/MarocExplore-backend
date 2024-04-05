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
            // <div key={index}>
            //     <img src={itinerarie.image} />
            // </div>
            <div  key={index} className="bg-white font-[sans-serif] my-4">
            <div className="max-w-7xl mx-auto">
              <div className="text-center">
                <h2 className="text-3xl font-extrabold text-[#333] inline-block relative after:absolute after:w-4/6 after:h-1 after:left-0 after:right-0 after:-bottom-4 after:mx-auto after:bg-pink-400 after:rounded-full">LATEST BLOGS</h2>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-16 max-md:max-w-lg mx-auto">
                <div className="bg-white cursor-pointer rounded overflow-hidden shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] relative top-0 hover:-top-2 transition-all duration-300">
                  <img src={itinerarie.image} alt="Blog Post 3" className="w-full h-60 object-cover" />
                  <div className="p-6">
                    <span className="text-sm block text-gray-400 mb-2">5 OCT 2023 | BY SIMON KONECKI</span>
                    <h3 className="text-xl font-bold text-[#333]">Trends and Predictions</h3>
                    <hr className="my-6" />
                    <p className="text-gray-400 text-sm">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis accumsan, nunc et tempus blandit, metus mi consectetur felis turpis vitae ligula.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
            )
        })}
        </>
    )
}