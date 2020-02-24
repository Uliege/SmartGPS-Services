import json
import pandas as pd

def getDataFrame(path):
    with open(path) as json_file:
        datos = json.load(json_file)
        ids = []
        dates = []
        grsX = []
        grsY = []
        grsZ = []
        aclX = []
        aclY = []
        aclZ = []
        proximity = []
        light = []
        stepCounter = []
        battery = []
        nSatellites = []
        accuracy = []
        latitude = []
        longitude = []
        velocity = []
        altitude = []
        activity = []
        activityConfidence = []
        temperature = []
        weather = []
        city = []
        fechaIn = []
        fechaUp = []
        prStatus = []
        for dato in datos:
            ids.append(dato['dspId'])
            dates.append(dato['fecha'])
            gX = "grsX" in dato['sensor']
            if gX:
                grsX.append(dato['sensor']['grsX'])
            else:
                grsX.append(None)
            gY = "grsY" in dato['sensor']
            if gY:
                grsY.append(dato['sensor']['grsY'])
            else:
                grsY.append(None)
            gZ = "grsZ" in dato['sensor']
            if gZ:
                grsZ.append(dato['sensor']['grsZ'])
            else:
                grsZ.append(None)
            aX = "aclX" in dato['sensor']
            if aX:
                aclX.append(dato['sensor']['aclX'])
            else:
                aclX.append(None)
            aY = "aclY" in dato['sensor']
            if aY:
                aclY.append(dato['sensor']['aclY'])
            else:
                aclY.append(None)
            aZ = "aclZ" in dato['sensor']
            if aZ:
                aclZ.append(dato['sensor']['aclZ'])
            else:
                aclZ.append(None)
            pr = "proximity" in dato['sensor']
            if pr:
                proximity.append(dato['sensor']['proximity'])
            else:
                proximity.append(None)
            li = "luminosity" in dato['sensor']
            if li:
                light.append(dato['sensor']['luminosity'])
            else:
                light.append(None)
            stC = "stepCounter" in dato['sensor']
            if stC:
                stepCounter.append(dato['sensor']['stepCounter'])
            else:
                stepCounter.append(None)
            bat = "battery" in dato['sensor']
            if bat:
                battery.append(dato['sensor']['battery'])
            else:
                battery.append(None)
            nSa = "nSatellites" in dato['sensor']
            if nSa:
                nSatellites.append(dato['sensor']['nSatellites'])
            else:
                nSatellites.append(None)
            ac = "accuracy" in dato['sensor']
            if ac:
                accuracy.append(dato['sensor']['accuracy'])
            else:
                accuracy.append(None)
            la = "latitude" in dato['sensor']
            if la:
                latitude.append(dato['sensor']['latitude'])
            else:
                latitude.append(None)
            lo = "longitude" in dato['sensor']
            if lo:
                longitude.append(dato['sensor']['longitude'])
            else:
                longitude.append(None)
            ve = "velocity" in dato['sensor']
            if ve:
                velocity.append(dato['sensor']['velocity'])
            else:
                velocity.append(None)
            al = "altitude" in dato['sensor']
            if al:
                altitude.append(dato['sensor']['altitude'])
            else:
                altitude.append(None)
            act = "activity" in dato['sensor']
            if act:
                activity.append(dato['sensor']['activity'])
            else:
                activity.append(None)
            actC = "activityConfidence" in dato['sensor']
            if actC:
                activityConfidence.append(dato['sensor']['activityConfidence'])
            else:
                activityConfidence.append(None)
            tem = "temperature" in dato['sensor']
            if tem:
                temperature.append(dato['sensor']['temperature'])
            else:
                temperature.append(None)
            we = "weather" in dato['sensor']
            if we:
                weather.append(dato['sensor']['weather'])
            else:
                weather.append(None)
            ci = "city" in dato['sensor']
            if ci:
                city.append(dato['sensor']['city'])
            else:
                city.append(None)
            dI = "dateInsert" in dato['sensor']
            if dI:
                fechaIn.append(dato['sensor']['dateInsert'])
            else:
                fechaIn.append(None)
            dU = "dateUpdate" in dato['sensor']
            if dU:
                fechaUp.append(dato['sensor']['dateUpdate'])
            else:
                fechaUp.append(None)
            pSt = "providerStatus" in dato['sensor']
            if pSt:
                prStatus.append(dato['sensor']['providerStatus'])
            else:
                prStatus.append(None)
        df = pd.DataFrame()
        df['dspId'] = ids
        # df['dspFecha'] = dates
        df['dspGrsX'] = grsX
        df['dspGrsY'] = grsY
        df['dspGrsZ'] = grsZ
        df['dspAclX'] = aclX
        df['dspAclY'] = aclY
        df['dspAclZ'] = aclZ
        df['dspProx'] = proximity
        df['dspLight'] = light
        df['dspStCount'] = stepCounter
        df['dspBattery'] = battery
        df['dspNumSatel'] = nSatellites
        df['dspAccu'] = accuracy
        df['dspLatitude'] = latitude
        df['dspLongitude'] = longitude
        df['dspVeloc'] = velocity
        df['dspAltitude'] = altitude
        df['dspActivity'] = activity
        df['dspActConfid'] = activityConfidence
        df['dspTemp'] = temperature
        df['dspWeather'] = weather
        df['dspCity'] = city
        df['dspFechIn'] = fechaIn
        df['dspFechUp'] = fechaUp
        df['dspPrStatus'] = prStatus
        #x = df.drop_duplicates()
        #print(df)
        #print(x)
        # Generar csv del dataframe
        # df.to_csv('numeros.csv')
        return df

