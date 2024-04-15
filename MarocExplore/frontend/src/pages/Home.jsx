import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { axiosClient } from "../config/axiosClient";
import getCookie from "../helper/cookie";

export default function Home() {

  const [itineraries, setItineraries] = useState([])
  const token = getCookie('ACCESS_TOKEN');

  useEffect(() => {
    const fetchData = async () => {
      await axiosClient.get("/itineraries").then(({ data }) => {
        setItineraries(data.itineraries)
      }).catch((errors) => {
        console.log(errors);
      })
    }

    fetchData();
  }, [])
  // Fonction pour formater la date
  const formatDate = (dateString) => {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', options);
  };
  
  console.log(itinerary.image);
  return (
    <>
      {token && (
        <div className="mt-8 flex items-center justify-center">
          <Link to="/createItinerary" className="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
            Create Itinerary
          </Link>
        </div>
      )}
      <div className="max-w-7xl mx-auto">
        <div className="text-center">
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mt-16 max-md:max-w-lg mx-auto">
          {itineraries.map((itinerary, index) => (
            <div key={index} className="bg-white cursor-pointer rounded overflow-hidden shadow-[0_2px_10px_-3px_rgba(6,81,237,0.3)] relative top-0 hover:-top-2 transition-all duration-300">
              <div
                className="w-full h-60 bg-cover bg-center"
                style={{ backgroundImage: `url(${itinerary.image})` }}
              />
              <div className="p-6">
                <span className="text-sm block text-gray-400 mb-2">{formatDate(itinerary.created_at)} | BY {itinerary.user.name}</span>
                <h3 className="text-xl font-bold text-[#333]">{itinerary.title}</h3>
                <hr className="my-6" />
                <div className="flex gap-2">

                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style={{ fill: "rgba(0, 0, 0, 1)" }}>
                    <path d="M21 20V6c0-1.103-.897-2-2-2h-2V2h-2v2H9V2H7v2H5c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2zM9 18H7v-2h2v2zm0-4H7v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm4 4h-2v-2h2v2zm0-4h-2v-2h2v2zm2-5H5V7h14v2z"></path>
                  </svg>
                  <p className="text-gray-900 text-sm">{itinerary.duration}</p>
                </div>

              </div>
            </div>
          ))}
        </div>
      </div >
    </>

  )
}