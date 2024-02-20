import ToBoolean from './toBoolean'

const VideoPlayControl = async (videoElement: HTMLVideoElement, state: boolean): Promise<void> => {
  if (state && videoElement.paused) {
    const isPlaying = ToBoolean(videoElement.dataset.playing!)
    if (isPlaying) return

    const playPromise = videoElement.play()
    if (playPromise === undefined) return

    playPromise
      .then(() => {
        // console.log('play', videoElement.src)
        videoElement.currentTime = 0
        videoElement.dataset.playing = 'true'
      })
      .catch((error) => {
        console.log(error)
      })
  }

  if (!state && !videoElement.paused) {
    const isPlaying = ToBoolean(videoElement.dataset.playing!)
    if (!isPlaying) return

    // console.log('pause', videoElement.src)
    videoElement.pause()
    videoElement.dataset.playing = 'false'
  }
}

export default VideoPlayControl
