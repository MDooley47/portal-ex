#!/usr/bin/env bash

echo "Updating environmental variables..."

var_file_location="/var/www/.env"

declare -A values

args=("$@")

for key in "${!args[@]}"
do
    if [ $(($key % 2)) -ne 0 ] && [[ ${args[$key]} != "" ]]
    then
        values[${args[$(($key-1))]}]=${args[$key]}
    fi
done

for key in "${!values[@]}"
do
    grep -q "^$key" $var_file_location && \
        sed -i "s/^\($key\).*/\1=$(eval echo \${values[$key]})/" $var_file_location || \
        echo "$key=${values[$key]}" >> $var_file_location
done

echo -e "\e[92mEnvironmental variables have been updated."
echo "The following is a list of environmental variables and their vaules."

echo -e "\e[34m"
cat $var_file_location
echo -e "\e[39m"
