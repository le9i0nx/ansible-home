#!/bin/bash
# Скрипт для изменения IP6 адреса
# ver 0.3 (2014.06.14)
# Для корректной работы скрипта
# wget,awk,grep должены быть установлены.
# mail le9i0nx@gmail.com
# strangled.net

procParmS()
{
   [ -z "$2" ] && return 1
   if [ "$1" = "$2" ] ; then
      cRes="$3"
      return 0
   fi
   return 1
}
procParmL()
{
   [ -z "$1" ] && return 1
         if [ "${2#$1=}" != "$2" ] ; then
      cRes="${2#$1=}"
      return 0
   fi
   return 1
}

while [ 1 ] ; do 
      if [ -z "$1" ] ; then
         break # Ключи кончились
# help
      elif [ "$1" = "-h" ] ; then 
         pHelp=1 
      elif [ "$1" = "--help" ] ; then 
         pHelp=1 
# 1. токен
      elif procParmS "-k" "$1" "$2" ; then 
         pKey="$cRes"
         pK=1 ; shift 
      elif procParmL "--key" "$1" ; then 
         pKey="$cRes" 
         pK=1

# 2. интерфейс
      elif procParmS "-i" "$1" "$2" ; then 
         pInt="$cRes"
         pI=1 ; shift 
      elif procParmL "--interface" "$1" ; then 
         pInt="$cRes" 
         pI=1

      else 
         echo "Ошибка: неизвестный ключ" 1>&2 
         exit 1 
      fi 
      shift 
done

#help
if [ "$pHelp" == "1" ] ; then 
   echo "   help" 1>&2 
   echo "      -k KEY" 1>&2 
   echo "      --key=KEY" 1>&2 
   echo "         Токен для сайта (можно взять в лк)" 1>&2 
   echo "      -i id " 1>&2 
   echo "      --interface=id" 1>&2 
   echo "         Сетевое устройство на котором ip6" 1>&2 
   exit 1
fi

if [ "$pK" == "1" ] ; then
	if [ "$pI" == "1" ] ; then
		ifconfig="/sbin/ifconfig"; #Путь до ifconfig
		# Узнаем свой IPv6
		ipv6=$($ifconfig $pInt | grep Scope:Global | awk '{print $3}' | awk -F"/" '{print $1}');
		# Четение старого IP
		oldip=$(cat /tmp/ipv6update_$pInt.txt);
		# если ip не меняется отдыхаем
		if [ "$ipv6" != "$oldip" ]
		then
			# теперь всё делаем одной строкой
			curl "http://freedns.afraid.org/dynamic/update.php?$pKey&address=$ipv6" 2>/dev/null
			# Запись IP в файл
			echo "$ipv6" > /tmp/ipv6update_$pInt.txt
		fi
		exit 0
	fi
fi

